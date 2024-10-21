<?php

namespace App\Http\Controllers;

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
        $dongSanPhams = dong_san_pham::with(['loai_san_pham.san_pham', 'loai_san_pham.san_pham.khuyen_mai_san_pham', 'loai_san_pham.san_pham.anh_san_pham'])->get();
    
        
        $data = [
            'dong_san_pham' => []
        ];
    
        foreach ($dongSanPhams as $dongSanPham) {
            
            $item = [
                'ma_dong_san_pham' => $dongSanPham->ma_dong_san_pham,
                'ten_dong_san_pham' => $dongSanPham->ten_dong_san_pham,
                'mo_ta'=>$dongSanPham->mo_ta,
                'created_at'=>$dongSanPham->created_at,
                'updated_at'=>$dongSanPham->updated_at,
                'loai_san_pham' => []
            ];
    
            foreach ($dongSanPham->loai_san_pham as $loaiSanPham) {
                
                $loaiItem = [
                    'ma_loai_san_pham' => $loaiSanPham->ma_loai_san_pham,
                    'ma_dong_san_pham'=>$loaiSanPham->ma_dong_san_pham,
                    'ten_loai_san_pham' => $loaiSanPham->ten_loai_san_pham,
                    'mo_ta' => $loaiSanPham->mo_ta,
                    'created_at'=>$loaiSanPham->created_at,
                    'updated_at'=>$loaiSanPham->updated_at,
                    'san_pham' => []
                ];
    
                foreach ($loaiSanPham->san_pham as $sanPham) { 
                   
                    $sanPhamItem = [
                        'ma_san_pham' => $sanPham->ma_san_pham,
                        'ma_loai_san_pham'=>$sanPham->ma_loai_san_pham,
                        
                        'ten_san_pham' => $sanPham->ten_san_pham,
                        'mau_sac' => $sanPham->mau_sac,
                        'tinh_trang' => $sanPham->tinh_trang,
                        'gia_ban' => $sanPham->gia_ban,
                        'mo_ta' => $sanPham->mo_ta,
                        'created_at' => $sanPham->created_at,
                        'updated_at' => $sanPham->updated_at,
                        'nha_cung_cap' => [
                            'ma_nha_cung_cap' => $sanPham->nha_cung_cap->ma_nha_cung_cap,
                            'ten_nha_cung_cap' => $sanPham->nha_cung_cap->ten_nha_cung_cap,
                            'dia_chi' => $sanPham->nha_cung_cap->dia_chi,
                            'so_dien_thoai' => $sanPham->nha_cung_cap->so_dien_thoai,
                            'email' => $sanPham->nha_cung_cap->email,
                            'created_at' => $sanPham->nha_cung_cap->created_at,
                            'updated_at' => $sanPham->nha_cung_cap->updated_at,
                        ],
                        'khuyen_mai_san_pham' => $sanPham->khuyen_mai_san_pham->map(function($khuyenMai) {
                            return [
                                'ma_khuyen_mai' => $khuyenMai->ma_khuyen_mai,
                                'muc_giam_gia' => $khuyenMai->muc_giam_gia,
                                'ngay_bat_dau' => $khuyenMai->ngay_bat_dau,
                                'ngay_ket_thuc' => $khuyenMai->ngay_ket_thuc,
                                'dieu_kien_ap_dung' => $khuyenMai->dieu_kien_ap_dung,
                                'created_at' => $khuyenMai->created_at,
                                'updated_at' => $khuyenMai->updated_at,
                            ];
                        }),
                        'anh_san_pham' => $sanPham->anh_san_pham->map(function($anh) {
                            return [
                                'ma_anh_san_pham' => $anh->ma_anh_san_pham,
                                'url_anh' => $anh->url_anh,
                                'la_anh_chinh' => $anh->la_anh_chinh,
                                'created_at' => $anh->created_at,
                                'updated_at' => $anh->updated_at,
                            ];
                        }),
                    ];
    
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
        
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])->get();
                    
        return response()->json($sanpham);
    }


    public function loc_san_pham_theo_loai(Request $request)
    {
        $sanpham=san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])->where('ma_loai_san_pham',$request->ma_loai_san_pham)->get();
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
            'ma_loai_san_pham' => 'required|exists:loai_san_pham,ma_loai_san_pham',
            'ma_nha_cung_cap' => 'required|exists:nha_cung_cap,ma_nha_cung_cap',
            'ten_san_pham' => 'required|string|max:255',
            'mau_sac' => 'nullable|string|max:255',
            'tinh_trang' => 'required|string|max:50',
            'gia_ban' => 'required|numeric|min:0', 
            'mo_ta' => 'nullable|string'
        ]);

        $sanpham = san_pham::create([
            'ma_loai_san_pham' => $request->input('ma_loai_san_pham'),
            'ma_nha_cung_cap' => $request->input('ma_nha_cung_cap'),
            'ten_san_pham' => $request->input('ten_san_pham'),
            'mau_sac' => $request->input('mau_sac'),
            'tinh_trang' => $request->input('tinh_trang'),
            'gia_ban' => $request->input('gia_ban'), 
            'mo_ta' => $request->input('mo_ta')
        ]);

        return response()->json([
            'message' => 'Thêm Thành Công!',
            'data' => $sanpham
        ], 201);
    }

    public function xoa_san_pham(Request $request)
    {
        $sanpham=san_pham::where('ma_san_pham',$request->input('ma_san_pham'))->first();
        $sanpham->delete();
        return Response()->json(['message'=>'Xóa Thành Công!'],200);
    }

    public function cap_nhat_san_pham(Request $request)
    {
        
        $request->validate([
            'ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'ma_loai_san_pham' => 'nullable|exists:loai_san_pham,ma_loai_san_pham',
            'ma_nha_cung_cap' => 'nullable|exists:nha_cung_cap,ma_nha_cung_cap',
            'ten_san_pham' => 'nullable|string|max:255',
            'mau_sac' => 'nullable|string|max:255',
            'tinh_trang' => 'nullable|string|max:50',
            'gia_ban' => 'nullable|numeric|min:0',
            'mo_ta' => 'nullable|string'
        ]);

        
        $sanpham = san_pham::where('ma_san_pham', $request->input('ma_san_pham'))->first();

        
        $sanpham->ma_loai_san_pham = $request->input('ma_loai_san_pham', $sanpham->ma_loai_san_pham);
        $sanpham->ma_nha_cung_cap = $request->input('ma_nha_cung_cap', $sanpham->ma_nha_cung_cap);
        $sanpham->ten_san_pham = $request->input('ten_san_pham', $sanpham->ten_san_pham);
        $sanpham->mau_sac = $request->input('mau_sac', $sanpham->mau_sac);
        $sanpham->tinh_trang = $request->input('tinh_trang', $sanpham->tinh_trang);
        $sanpham->gia_ban = $request->input('gia_ban', $sanpham->gia_ban);
        $sanpham->mo_ta = $request->input('mo_ta', $sanpham->mo_ta);

        
        $sanpham->save();

        
        return response()->json([
            'message' => 'Cập nhật thành công!',
            'data' => $sanpham
        ], 200);
    }

    public function lay_san_pham_form(Request $request)
    {
        $sosanpham = $request->input('so_san_pham', 4);
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
                    ->paginate($sosanpham);
        return response()->json($sanpham);
    }


}
