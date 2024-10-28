<?php

namespace App\Http\Controllers;

use App\Models\chi_tiet_gio_hang;
use App\Models\gio_hang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class gio_hangController extends Controller
{
    //
    // Thêm sản phẩm vào giỏ hàng
    public function themSP_GH(Request $request)
    {
        // Validate input
        $request->validate([
            'ma_san_pham' => 'required|integer|exists:san_pham,ma_san_pham',
            'so_luong' => 'required|integer|min:1',
        ]);

        // Lấy thông tin người dùng
        $userId = Auth::id();

        // Kiểm tra xem giỏ hàng của người dùng đã tồn tại hay chưa
        $gioHang = gio_hang::where('ma_user', $userId)->where('trang_thai', 'active')->first();

        // Nếu giỏ hàng chưa tồn tại, tạo mới
        if (!$gioHang) {
            $gioHang = gio_hang::create([
                'ma_user' => $userId,
                'ngay_tao' => now(),
                'trang_thai' => 'active',
            ]);
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $chiTietGioHang = chi_tiet_gio_hang::where('ma_gio_hang', $gioHang->ma_gio_hang)
            ->where('ma_san_pham', $request->ma_san_pham)
            ->first();

        if ($chiTietGioHang) {
            // Nếu sản phẩm đã tồn tại trong giỏ hàng, cập nhật số lượng
            $chiTietGioHang->so_luong += $request->so_luong;
            $chiTietGioHang->gia_ban = $chiTietGioHang->gia_ban; // Cập nhật giá nếu cần
            $chiTietGioHang->save();
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            chi_tiet_gio_hang::create([
                'ma_gio_hang' => $gioHang->ma_gio_hang,
                'ma_san_pham' => $request->ma_san_pham,
                'so_luong' => $request->so_luong,
                'gia_ban' => $request->gia_ban, // Giả sử giá bán được truyền trong request
            ]);
        }

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công!'], 200);
    }
}
