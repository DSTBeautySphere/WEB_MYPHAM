<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\san_pham;
use App\Models\loai_san_pham;
use App\Models\dong_san_pham;
use GuzzleHttp\Psr7\Response;

class san_phamController extends Controller
{
    //
    public function view_san_pham()
    {
        return view('sanpham');
    }

    public function lay_san_pham()
    {
        $sanPhams = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])->get();     
        return response()->json($sanPhams);
    }

    public function lay_san_pham_phan_trang(Request $request)
    {
        $sosanpham = $request->input('so_san_pham', 4);
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
                    ->paginate($sosanpham);
        return response()->json($sanpham);
    }


    public function loc_san_pham_theo_loai(Request $request)
    {
        $sanpham=san_pham::where('ma_loai_san_pham',$request->ma_loai_san_pham)->get();
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


}
