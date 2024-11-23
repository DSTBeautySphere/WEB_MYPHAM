<?php

namespace App\Http\Controllers;

use App\Models\chi_tiet_don_dat;
use App\Models\chi_tiet_gio_hang;
use Illuminate\Http\Request;
use App\Models\don_dat;
use App\Models\gio_hang;

class don_datController extends Controller
{
    //
    public function themDonDat(Request $request)
    {
        $donDat = don_dat::create([
            'ma_user' => 1,
            'ngay_dat' => $request->ngay_dat,
            'tong_tien' => $request->tong_tien,
            'trang_thai' => $request->trang_thai,
        ]);

        $giohang = gio_hang::where('ma_user', 1)->where('trang_thai', 'active')->first();

        if($giohang){
            $chitietgiohang = chi_tiet_gio_hang::where('ma_gio_hang', $giohang->ma_gio_hang)->get();
            foreach($chitietgiohang as $ctgh){
                chi_tiet_don_dat::create([
                    'ma_don_dat' => $donDat->ma_don_dat,
                    'ma_bien_the' => $ctgh->ma_bien_the,
                    'so_luong' => $ctgh->so_luong,
                    'gia_ban' => $ctgh->gia_ban
                ]);

                $ctgh->delete();
            }
            $giohang->delete();
        }


        return response()->json(['message' => 'Đơn hàng đã đặt thành công!'], 200);
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
