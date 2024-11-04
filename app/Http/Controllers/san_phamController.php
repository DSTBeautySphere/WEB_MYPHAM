<?php

namespace App\Http\Controllers;

use App\Models\bien_the_san_pham;
use Illuminate\Http\Request;
use App\Models\san_pham;
use App\Models\loai_san_pham;
use App\Models\dong_san_pham;
use App\Models\nha_cung_cap;
use GuzzleHttp\Psr7\Response;

class san_phamController extends Controller
{
    //
    public function view_san_pham()
    {
        return view('sanpham');
    }

    public function lay_san_pham_all()
    {
        $dongSanPhams = dong_san_pham::all();     
        $data = [
            'dong_san_pham' => []
        ];
    
        foreach ($dongSanPhams as $dongSanPham) {
            
            $item = [
                'ma_dong_san_pham' => $dongSanPham->ma_dong_san_pham,
                'ten_dong_san_pham' => $dongSanPham->ten_dong_san_pham,
                'mo_ta'=>$dongSanPham->mo_ta,
                'loai_san_pham' => []
            ];
    
            foreach ($dongSanPham->loai_san_pham as $loaiSanPham) {
                
                $loaiItem = [
                    'ma_loai_san_pham' => $loaiSanPham->ma_loai_san_pham,
                    'ma_dong_san_pham'=>$loaiSanPham->ma_dong_san_pham,
                    'ten_loai_san_pham' => $loaiSanPham->ten_loai_san_pham,
                    'mo_ta' => $loaiSanPham->mo_ta,
                    'san_pham' => []
                ];
    
                foreach ($loaiSanPham->san_pham as $sanPham) { 
                   
                    $sanPhamItem = [
                        'ma_san_pham' => $sanPham->ma_san_pham,
                        'ma_loai_san_pham'=>$sanPham->ma_loai_san_pham,                
                        'ten_san_pham' => $sanPham->ten_san_pham,
                        'bien_the_san_pham'=>[],
                        'nha_cung_cap' => [
                            'ma_nha_cung_cap' => $sanPham->nha_cung_cap->ma_nha_cung_cap,
                            'ten_nha_cung_cap' => $sanPham->nha_cung_cap->ten_nha_cung_cap,
                            'dia_chi' => $sanPham->nha_cung_cap->dia_chi,
                            'so_dien_thoai' => $sanPham->nha_cung_cap->so_dien_thoai,
                            'email' => $sanPham->nha_cung_cap->email,
                        ],
                        'khuyen_mai_san_pham' => $sanPham->khuyen_mai_san_pham->map(function($khuyenMai) {
                            return [
                                'ma_khuyen_mai' => $khuyenMai->ma_khuyen_mai,
                                'muc_giam_gia' => $khuyenMai->muc_giam_gia,
                                'ngay_bat_dau' => $khuyenMai->ngay_bat_dau,
                                'ngay_ket_thuc' => $khuyenMai->ngay_ket_thuc,
                                'dieu_kien_ap_dung' => $khuyenMai->dieu_kien_ap_dung,
                            ];
                        }),
                        'anh_san_pham' => $sanPham->anh_san_pham->map(function($anh) {
                            return [
                                'ma_anh_san_pham' => $anh->ma_anh_san_pham,
                                'url_anh' => $anh->url_anh,
                                'la_anh_chinh' => $anh->la_anh_chinh,
                            ];
                        }),
                    ];

                    foreach($sanPham->bien_the_san_pham as $bthe_sp)
                    {
                        $bienthesp=[
                            'ma_bien_the'=>$bthe_sp->ma_bien_the,
                            'ma_san_pham'=>$bthe_sp->ma_san_pham,
                            'mau_sac'=>$bthe_sp->mau_sac,
                            'loai_da'=>$bthe_sp->loai_da,
                            'dung_tich'=>$bthe_sp->dung_tich,
                            'so_luong_ton_kho'=>$bthe_sp->so_luong_ton_kho,
                            'gia_ban'=>$bthe_sp->gia_ban,
                        ];

                        $sanPhamItem['bien_the_san_pham'][]=$bienthesp;

                    }
    
                    $loaiItem['san_pham'][] = $sanPhamItem; 
                }
    
                $item['loai_san_pham'][] = $loaiItem; 
            }
    
            $data['dong_san_pham'][] = $item; 
        }
    
        // Trả về dữ liệu dưới dạng JSON
        return response()->json($data);
    }
    
    public function lay_san_pham_phan_trang(Request $request)
    {
        $sosanpham = $request->input('so_san_pham', 4);
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
                    ->paginate($sosanpham);
        return response()->json($sanpham);
    }

    public function lay_san_pham()
    {
        
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham','bien_the_san_pham'])->get();
        
        return response()->json($sanpham);
    }


    public function loc_san_pham_theo_loai(Request $request)
    {

        $sanpham=san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham','bien_the_san_pham'])->where('ma_loai_san_pham',$request->ma_loai_san_pham)->get();
        return response()->json($sanpham,200);


    }

    public function loc_san_pham_theo_dong(Request $request)
    {
        $loaiSanPham = loai_san_pham::where('ma_dong_san_pham', $request->input('ma_dong_san_pham'))->get();
        $maLoaiSanPham = $loaiSanPham->pluck('ma_loai_san_pham');
        $sanpham = san_pham::whereIn('ma_loai_san_pham', $maLoaiSanPham)->get();
        return response()->json($sanpham,200);
    }

    public function chi_tiet_san_pham(Request $request)
    {
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
        ->where('ma_san_pham', $request->ma_san_pham)
        ->first();
        return response()->json($sanpham,200);
    }

    public function loc_san_pham_theo_gia(Request $request)
    {
        $min = $request->input('min', 0);
        $max = $request->input('max', 0);

        $sanpham = san_pham::when($min != 0 && $max == 0, function ($query) use ($min) {
            return $query->where('gia_ban', '>=', $min);
        })
        ->when($min == 0 && $max != 0, function ($query) use ($max) {
            return $query->where('gia_ban', '<=', $max);
        })
        ->when($min != 0 && $max != 0, function ($query) use ($min, $max) {
            return $query->whereBetween('gia_ban', [$min, $max]);
        })
        ->get();

        return response()->json($sanpham, 200);
    }

    public function tim_san_pham(Request $request)
    {

    }

    public function them_san_pham(Request $request)
    {
            $request->validate([
                'ma_loai_san_pham' => 'required|integer|exists:loai_san_pham,ma_loai_san_pham',
                'ma_nha_cung_cap' => 'required|integer|exists:nha_cung_cap,ma_nha_cung_cap',
                'ten_san_pham' => 'required|string|max:255',
                'bien_the' => 'required|array',
                'bien_the.*.mau_sac' => 'required|string|max:50',
                'bien_the.*.loai_da' => 'required|string|max:50',
                'bien_the.*.dung_tich' => 'required|numeric|min:0',
                'bien_the.*.so_luong_ton_kho' => 'required|integer|min:0',
                'bien_the.*.gia_ban' => 'required|numeric|min:0',
            ]);

            try {
                // Tạo sản phẩm mới
                $sanPham = san_pham::create([
                    'ma_loai_san_pham' => $request->ma_loai_san_pham,
                    'ma_nha_cung_cap' => $request->ma_nha_cung_cap,
                    'ten_san_pham' => $request->ten_san_pham,
                ]);

                // $bienTheList = [];
                // // Duyệt qua từng biến thể để thêm vào bảng `bien_the_san_pham`
                // foreach ($request->bien_the as $bienTheData) {
                //     $bienThe = bien_the_san_pham::create([
                //         'ma_san_pham' => $sanPham->ma_san_pham,
                //         'mau_sac' => $bienTheData['mau_sac'],
                //         'loai_da' => $bienTheData['loai_da'],
                //         'dung_tich' => $bienTheData['dung_tich'],
                //         'so_luong_ton_kho' => $bienTheData['so_luong_ton_kho'],
                //         'gia_ban' => $bienTheData['gia_ban'],
                //     ]);
                //     $bienTheList[] = $bienThe; 
                // }

               
                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm được thêm thành công.',
                    'data' => [
                        'san_pham' => $sanPham,
                        //'bien_the' => $bienTheList
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi thêm sản phẩm: ' . $e->getMessage()
                ]);
            }
        }


   

    public function xoa_san_pham(Request $request)
    {
        $sanpham=san_pham::where('ma_san_pham',$request->input('ma_san_pham'))->first();
        $sanpham->delete();
        return Response()->json(['message'=>'Xóa Thành Công!'],200);
    }

    public function cap_nhat_san_pham(Request $request)
    {
            // Xác thực yêu cầu
        $request->validate([
            'ma_san_pham' => 'required|integer|exists:san_pham,ma_san_pham',
            'ma_loai_san_pham' => 'required|integer|exists:loai_san_pham,ma_loai_san_pham',
            'ma_nha_cung_cap' => 'required|integer|exists:nha_cung_cap,ma_nha_cung_cap',
            'ten_san_pham' => 'required|string|max:255',
        ]);

        try {
          
            $sanPham = san_pham::find($request->ma_san_pham);

            
            $sanPham->update([
                'ma_loai_san_pham' => $request->ma_loai_san_pham,
                'ma_nha_cung_cap' => $request->ma_nha_cung_cap,
                'ten_san_pham' => $request->ten_san_pham,
            ]);

            
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được cập nhật thành công.',
                'data' => $sanPham
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage()
            ]);
        }
        
    }

    public function lay_san_pham_form(Request $request)
    {
        $sosanpham = $request->input('so_san_pham', 4);
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
                    ->paginate($sosanpham);
        return response()->json($sanpham);
    }


}
