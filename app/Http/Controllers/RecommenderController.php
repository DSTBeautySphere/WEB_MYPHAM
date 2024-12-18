<?php

namespace App\Http\Controllers;

use App\Models\bien_the_san_pham;
use App\Models\products_prediction;
use App\Models\san_pham;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Phpml\Classification\DecisionTree;
use Phpml\Dataset\ArrayDataset;

use Phpml\ModelManager;

class RecommenderController extends Controller
{
    public function timSanPhamTuongTu(Request $request): JsonResponse
    {
        try {
            $maSanPham = $request->input('ma_san_pham');
            $bienThe = bien_the_san_pham::where("ma_san_pham", $maSanPham)->first();
            while (!$bienThe && $maSanPham > 0) {
                $maSanPham--; 
                $bienThe = bien_the_san_pham::where("ma_san_pham", $maSanPham)->first();
            }
            if (!$bienThe) {
                return response()->json([
                    'message' => 'Không tìm thấy sản phẩm hoặc sản phẩm không có biến thể.',
                ], 404);
            }
            $soGoiY = $request->input('so_goi_y', 5);
            $sanPham = DB::table('san_pham as sp')
                ->join('bien_the_san_pham as btsp', 'sp.ma_san_pham', '=', 'btsp.ma_san_pham')
                ->join('loai_san_pham as lsp', 'sp.ma_loai_san_pham', '=', 'lsp.ma_loai_san_pham')
                ->select([ 
                    'sp.ma_san_pham', 
                    DB::raw('COALESCE(sp.ten_san_pham, "") as Name'),
                    DB::raw('COALESCE(sp.trang_thai, "") as TrangThaiSanPham'),
                    DB::raw('COALESCE(btsp.mau_sac, "") as MauSac'),
                    DB::raw('COALESCE(btsp.loai_da, "") as LoaiDa'),
                    DB::raw('COALESCE(btsp.dung_tich, "") as DungTich'),
                    DB::raw('COALESCE(btsp.gia_ban, 0) as Gia'),
                    DB::raw('COALESCE(btsp.trang_thai, "") as TrangThaiBienThe'),
                    DB::raw('COALESCE(lsp.ten_loai_san_pham, "") as TenLoaiSanPham'),
                ])
                ->get();       
            if ($sanPham->isEmpty()) {
                return response()->json([
                    'message' => 'Không tìm thấy sản phẩm nào trong cơ sở dữ liệu.',
                ], 404);
            }
            $sanPham = $sanPham->map(function ($item) {
                return (array)$item;
            });
            $sanPham = collect($sanPham)->map(function ($item) {
                $item['combinedFeatures'] = implode(' ', array_filter([ 
                    $item['Name'] ?? '',
                    $item['MauSac'] ?? '',
                    $item['LoaiDa'] ?? '',
                    $item['DungTich'] ?? '',
                    $item['Gia'] ?? 0,
                    $item['TenLoaiSanPham'] ?? '',
                ]));
                return $item;
            });
            $features = $sanPham->pluck('combinedFeatures')->toArray();
            if (empty($features)) {
                return response()->json([
                    'message' => 'Không có dữ liệu đặc trưng hợp lệ để tính toán độ tương đồng.',
                ], 400);
            }
            $tfMatrix = $this->calculateTfIdfMatrix($features);
            $similarityMatrix = $this->calculateCosineSimilarity($tfMatrix);
            $selectedIndex = $sanPham->search(fn($item) => $item['ma_san_pham'] == $maSanPham);
            if ($selectedIndex === false) {
                return response()->json([
                    'message' => 'Không tìm thấy sản phẩm được chọn.',
                ], 404);
            }
            $similarProducts = collect($similarityMatrix[$selectedIndex])
                ->map(fn($similarity, $index) => ['index' => $index, 'similarity' => $similarity])
                ->sortByDesc('similarity')
                ->skip(1) 
                ->map(function ($item) use ($sanPham) {
                    $product = $sanPham[$item['index']];
                    return [
                        'ma_san_pham' => $product['ma_san_pham'],
                        'ten_san_pham' => $product['Name'],
                        'do_tuong_dong' => $item['similarity'],
                    ];
                });
            $similarProducts = $similarProducts->filter(function ($item) use ($sanPham, $maSanPham) {
                $selectedProduct = $sanPham->firstWhere('ma_san_pham', $maSanPham);
                return $item['ten_san_pham'] !== $selectedProduct['Name'];
            });
            $validProducts = [];
            foreach ($similarProducts as $item) {
                if (!in_array($item['ma_san_pham'], array_column($validProducts, 'ma_san_pham'))) {
                    
                    // Kiểm tra xem sản phẩm có hợp lệ hay không
                    $ktsp = san_pham::with(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham', 'khuyen_mai_san_pham'])
                        ->withCount(['danh_gia as so_sao_trung_binh' => function ($query) {
                            $query->select(DB::raw('ROUND(AVG(so_sao), 0)')); // Làm tròn số sao trung bình
                        }])
                        ->whereHas('bien_the_san_pham')
                        ->where('trang_thai', "1") 
                        ->where('ma_san_pham', $item['ma_san_pham']) 
                        ->first(); 
            
                    // Nếu sản phẩm hợp lệ, thêm vào danh sách sản phẩm hợp lệ
                    if ($ktsp) {
                        $validProducts[] = $ktsp;
                    } else {
                      
                        $soGoiY++;
                    }
                }
                if (count($validProducts) >= $soGoiY) {
                    break;
                }
            }
            
            
            // Truy vấn danh sách sản phẩm gợi ý cuối cùng
            $recommendedProducts = san_pham::with(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham', 'khuyen_mai_san_pham'])
                ->withCount(['danh_gia as so_sao_trung_binh' => function ($query) {
                    $query->select(DB::raw('ROUND(AVG(so_sao), 0)')); // Làm tròn số sao trung bình
                }])
                ->whereHas('bien_the_san_pham') // Chỉ chọn sản phẩm có biến thể
                ->where('trang_thai', "1") // Trạng thái phải là 1
                ->whereIn('ma_san_pham', array_column($validProducts, 'ma_san_pham')) // Chỉ lấy sản phẩm hợp lệ
                ->get();
            
            return response()->json($recommendedProducts);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi xử lý dữ liệu.',
            ], 500);
        }
    }
    private function calculateTfIdfMatrix($features)
    {
        $numDocuments = count($features);
        $documentTermMatrix = [];
        foreach ($features as $doc) {
            $terms = explode(' ', $doc);
            $termFrequency = array_count_values($terms);
            $documentTermMatrix[] = $termFrequency;
        }
        $allTerms = [];
        foreach ($documentTermMatrix as $termFreq) {
            $allTerms = array_merge($allTerms, array_keys($termFreq));
        }
        $allTerms = array_unique($allTerms);
        $idf = [];
        foreach ($allTerms as $term) {
            $idf[$term] = log($numDocuments / (1 + array_sum(array_map(fn($doc) => isset($doc[$term]) ? 1 : 0, $documentTermMatrix))));
        }
        $tfIdfMatrix = [];
        foreach ($documentTermMatrix as $doc) {
            $tfIdfDoc = [];
            foreach ($doc as $term => $count) {
                $tfIdfDoc[$term] = ($count / count($doc)) * $idf[$term];
            }
            $tfIdfMatrix[] = $tfIdfDoc;
        }

        return $tfIdfMatrix;
    }
    private function calculateCosineSimilarity($tfMatrix)
    {
        $similarityMatrix = [];
        foreach ($tfMatrix as $i => $vectorA) {
            foreach ($tfMatrix as $j => $vectorB) {
                $similarityMatrix[$i][$j] = $this->cosineSimilarity($vectorA, $vectorB);
            }
        }
        return $similarityMatrix;
    }
    private function cosineSimilarity($vectorA, $vectorB)
    {
        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        foreach ($vectorA as $term => $value) {
            $dotProduct += $value * ($vectorB[$term] ?? 0);
            $magnitudeA += $value * $value;
        }

        foreach ($vectorB as $value) {
            $magnitudeB += $value * $value;
        }

        return $dotProduct / (sqrt($magnitudeA) * sqrt($magnitudeB));
    }

    public function goiYSanPhamDT()
    {
       
        $data = products_prediction::all();
        $samples = [];
        $labels = [];
        foreach ($data as $item) {
            $samples[] = [
                $item->Price_USD, 
                $this->encodeSkinType($item->Skin_Type), 
                $this->encodeProductSize($item->Product_Size) 
            ];
            $labels[] = $item->is_purchased; 
        }
        $classifier = new DecisionTree();
        $classifier->train($samples, $labels);
        $bienTheVariants = bien_the_san_pham::all();
        $suggestedProducts = [];
        $addedProductIds = []; 

        foreach ($bienTheVariants as $variant) {
            $query = [];
            if ($variant->gia_ban) {
                $query[] = $variant->gia_ban / 23000; 
            } else {
                $query[] = 0;
            }
            if (!empty($variant->loai_da) && $variant->loai_da != 'N/A') {
                $query[] = $this->encodeSkinType($variant->loai_da);
            } else {
                $query[] = 0; 
            }
            if (!empty($variant->dung_tich) && $variant->dung_tich != 'N/A') {
                $query[] = $this->encodeProductSize($variant->dung_tich);
            } else {
                $query[] = 0; 
            }
            $prediction = $classifier->predict($query);
            Log::debug('Query for variant ' . $variant->ma_bien_the . ' kkkkkkk ' . implode(', ', $query) . ' ket qua ' . $prediction);

            if ($prediction === 'Yes' && !in_array($variant->san_pham->ma_san_pham, $addedProductIds)) {
               
                $suggestedProducts[] = $variant->san_pham->load(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham', 'khuyen_mai_san_pham']);
                $addedProductIds[] = $variant->san_pham->ma_san_pham; 
            }
        }
        return response()->json($suggestedProducts);
    }

    
    

private function encodeSkinType($skinType)
{
    $mapping = [
        'Sensitive' => 1,
        'Normal' => 2,
        'Dry' => 3,
        'Combination' => 4,
        'Oily' => 5,
        'Da Nhạy Cảm' => 1, 
        'Da Thường' => 2,    
        'Da Khô' => 3,       
        'Da Dầu' => 4,       
        'Da Hỗn Hợp' => 5    
    ];
    return $mapping[$skinType] ?? 0;
}

private function encodeProductSize($size)
{
    $sizeValue = (int) filter_var($size, FILTER_SANITIZE_NUMBER_INT);
    if ($sizeValue < 10 || $sizeValue > 500) {
        return 0;
    }
    return $sizeValue;
}

    

    

}
