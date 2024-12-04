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
                ->select([ 
                    'sp.ma_san_pham', 
                    DB::raw('COALESCE(sp.ten_san_pham, "") as Name'),
                    DB::raw('COALESCE(sp.trang_thai, "") as TrangThaiSanPham'),
                    DB::raw('COALESCE(btsp.mau_sac, "") as MauSac'),
                    DB::raw('COALESCE(btsp.loai_da, "") as LoaiDa'),
                    DB::raw('COALESCE(btsp.dung_tich, "") as DungTich'),
                    DB::raw('COALESCE(btsp.gia_ban, 0) as Gia'),
                    DB::raw('COALESCE(btsp.trang_thai, "") as TrangThaiBienThe'),
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
            $uniqueProducts = [];
            foreach ($similarProducts as $item) {
                if (!in_array($item['ma_san_pham'], array_column($uniqueProducts, 'ma_san_pham'))) {
                    $uniqueProducts[] = $item;
                }
                if (count($uniqueProducts) >= $soGoiY) {
                    break; 
                }
            }
            $recommendedProducts = san_pham::with(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham', 'khuyen_mai_san_pham'])->whereIn('ma_san_pham', array_column($uniqueProducts, 'ma_san_pham'))->get();

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
        
        // Chuẩn bị samples và labels cho quá trình huấn luyện
        foreach ($data as $item) {
            $samples[] = [
                $item->Price_USD, // Giá đã ở USD
                $this->encodeSkinType($item->Skin_Type), // Mã hóa loại da
                $this->encodeProductSize($item->Product_Size) // Mã hóa kích thước sản phẩm
            ];
            $labels[] = $item->is_purchased; // Dự đoán mua sản phẩm hay không (0 hoặc 1)
        }

        // Huấn luyện cây quyết định
        $classifier = new DecisionTree();
        $classifier->train($samples, $labels);

        // Lấy tất cả biến thể sản phẩm
        $bienTheVariants = bien_the_san_pham::all();
        $suggestedProducts = [];
        $addedProductIds = []; // Mảng để theo dõi các ID sản phẩm đã thêm

        foreach ($bienTheVariants as $variant) {
            // Kiểm tra và chuẩn bị dữ liệu đầu vào cho cây quyết định
            $query = [];

            // Chuyển đổi giá từ VND sang USD nếu có
            if ($variant->gia_ban) {
                $query[] = $variant->gia_ban / 23000; 
            } else {
                $query[] = 0;
            }

            // Xử lý loại da, nếu loại da không có thì không đưa vào cây quyết định
            if (!empty($variant->loai_da) && $variant->loai_da != 'N/A') {
                $query[] = $this->encodeSkinType($variant->loai_da);
            } else {
                $query[] = 0; // Nếu loại da không có, cho giá trị mặc định
            }

            // Xử lý dung tích, nếu dung tích không có thì không đưa vào cây quyết định
            if (!empty($variant->dung_tich) && $variant->dung_tich != 'N/A') {
                $query[] = $this->encodeProductSize($variant->dung_tich);
            } else {
                $query[] = 0; // Nếu dung tích không có, cho giá trị mặc định
            }

            // Dự đoán xem biến thể sản phẩm có được mua hay không
            $prediction = $classifier->predict($query);
            Log::debug('Query for variant ' . $variant->ma_bien_the . ' kkkkkkk ' . implode(', ', $query) . ' ket qua ' . $prediction);

            // Nếu dự đoán là 1 (mua sản phẩm) và sản phẩm chưa được thêm vào danh sách gợi ý
            if ($prediction === 'Yes' && !in_array($variant->san_pham->ma_san_pham, $addedProductIds)) {
                // Thêm sản phẩm vào danh sách gợi ý và mảng theo dõi ID
                $suggestedProducts[] = $variant->san_pham->load(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham', 'khuyen_mai_san_pham']);
                $addedProductIds[] = $variant->san_pham->ma_san_pham; // Lưu lại ID của sản phẩm đã thêm
            }
        }

        // Trả về các sản phẩm gợi ý dưới dạng JSON
        return response()->json($suggestedProducts);
    }

    
    

private function encodeSkinType($skinType)
{
    // Mã hóa các loại da bằng tiếng Việt
    $mapping = [
        'Sensitive' => 1,
        'Normal' => 2,
        'Dry' => 3,
        'Combination' => 4,
        'Oily' => 5,
        'Da Nhạy Cảm' => 1, // Sensitive
        'Da Thường' => 2,    // Normal
        'Da Khô' => 3,       // Dry
        'Da Dầu' => 4,       // Oily
        'Da Hỗn Hợp' => 5    // Combination
    ];

    // Trả về giá trị mã hóa hoặc 0 nếu loại da không được nhận diện
    return $mapping[$skinType] ?? 0;
}

private function encodeProductSize($size)
{
    // Trích xuất giá trị số từ dung tích sản phẩm (ví dụ: '100ml' thành 100)
    $sizeValue = (int) filter_var($size, FILTER_SANITIZE_NUMBER_INT);
    if ($sizeValue < 10 || $sizeValue > 500) {
        return 0; // Trả về 0 nếu dung tích không hợp lệ
    }
    return $sizeValue;
}

    

    

}
