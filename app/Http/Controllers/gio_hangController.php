<?php

namespace App\Http\Controllers;

use App\Models\chi_tiet_gio_hang;
use App\Models\gio_hang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class gio_hangController extends Controller
{
    //Hien chi tiet gio hang
    public function chiTietGioHang(Request $request){
        try{
            $userId= Auth::id();
            $gioHang= gio_hang::where('ma_user',$userId)->with('user')->first();

            if (!$gioHang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng của bạn trống.'
                ]);
            }

            $chiTietGH= chi_tiet_gio_hang::where('ma_gio_hang',$gioHang->ma_gio_hang)->with(['bien_the_san_pham'])->get();
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách chi tiết giỏ hàng thành công.',
                'gio_hang' => $gioHang,
                'chi_tiet_gio_hang' => $chiTietGH
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách chi tiết giỏ hàng: ' . $e->getMessage()
            ]);
        }
    }



    // Thêm sản phẩm vào giỏ hàng
    public function themSP_GH(Request $request)
    {
      
        $request->validate([
            'ma_bien_the' => 'required|integer|exists:san_pham,ma_san_pham',
            'so_luong' => 'required|integer|min:1',
        ]);

       
        $userId = Auth::id();

        
        $gioHang = gio_hang::where('ma_user', $userId)->where('trang_thai', 'active')->first();

       
        if (!$gioHang) {
            $gioHang = gio_hang::create([
                'ma_user' => $userId,
                'ngay_tao' => now(),
                'trang_thai' => 'active',
            ]);
        }

       
        $chiTietGioHang = chi_tiet_gio_hang::where('ma_gio_hang', $gioHang->ma_gio_hang)
            ->where('ma_bien_the', $request->ma_bien_the)
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
                'ma_bien_the' => $request->ma_bien_the,
                'so_luong' => $request->so_luong,
                'gia_ban' => $request->gia_ban, // Giả sử giá bán được truyền trong request
            ]);
        }

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công!'], 200);
    }
}
