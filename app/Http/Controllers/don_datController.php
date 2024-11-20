<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\don_dat;

class don_datController extends Controller
{
    //
    public function themDonDat(Request $request)
    {
        $donDat = don_dat::create([
            'ma_user' => $request->ma_user,
            'ngay_dat' => $request->ngay_dat,
            'tong_tien' => $request->tong_tien,
            'trang_thai' => $request->trang_thai,
        ]);

        

        return response()->json($donDat);
    }


     // Hàm để hiển thị danh sách đơn hàng
    public function danhSach()
    {
        // Lấy tất cả các đơn hàng cùng thông tin liên quan
        $donDat = don_dat::with(['chi_tiet_don_dat', 'hoa_don'])->get();

        return view('donhang.quan-ly-don-hang', compact('donDat'));
    }

    // Hàm để xem chi tiết đơn hàng
    public function show($id)
    {
        // Tìm đơn hàng theo ma_don_dat
        $donDat = don_dat::with(['chi_tiet_don_dat', 'hoa_don'])->findOrFail($id);

        // Trả về view với chi tiết đơn hàng
        return response()->json($donDat);
    }

}
