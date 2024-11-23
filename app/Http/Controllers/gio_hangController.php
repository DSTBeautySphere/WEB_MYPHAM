<?php

namespace App\Http\Controllers;

use App\Models\bien_the_san_pham;
use App\Models\chi_tiet_gio_hang;
use App\Models\gio_hang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class gio_hangController extends Controller
{
    public function chiTietGioHang(Request $request)
    {
        try {
            // Giả sử ID người dùng là 1, có thể thay đổi hoặc lấy từ auth
            $userId = 1;
    
            // Lấy giỏ hàng của người dùng
            $gioHang = gio_hang::where('ma_user', $userId)->with([
                'user',
                'bien_the_san_pham.san_pham.anh_san_pham',  // ảnh sản phẩm
                'bien_the_san_pham.san_pham'
            ])->get();
    
            // Trả về kết quả
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách chi tiết giỏ hàng thành công.',
                'gio_hang' => $gioHang
            ]);
        } catch (\Exception $e) {
            // Trả về thông báo lỗi
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

       
        $userId = 1;

        $gioHang = gio_hang::where('ma_user', $userId)->where('ma_bien_the', $request->ma_bien_the)->where('trang_thai', 'active')->first();

       
        if (!$gioHang) {
            $gioHang = gio_hang::create([
                'ma_user' => 1,
                'ma_bien_the'=> $request->ma_bien_the,
                'so_luong'=> $request->so_luong,
                'gia_ban'=> $request->gia_ban,
                'ngay_tao' => now(),
                'trang_thai' => 'active',
            ]);
        }else{
            $gioHang->so_luong += $request->so_luong;
            $gioHang->gia_ban += $gioHang->gia_ban;
            $gioHang->save();
        }

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công!'], 200);
    }
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'ma_bien_the' => 'required|integer|exists:san_pham,ma_san_pham',
            'so_luong' => 'required|integer|min:0',
        ]);

        $gioHang = gio_hang::where('ma_user', 1)->where('ma_bien_the', $request->ma_bien_the)->where('trang_thai', 'active')->first();

        $bien_the_san_pham = bien_the_san_pham::find($request->ma_bien_the);

        if (!$gioHang) {
            return response()->json(['message' => 'Giỏ hàng không tồn tại'], 404);
        }

        $gioHang->so_luong = $request->so_luong;
        $gioHang->gia_ban = $bien_the_san_pham->gia_ban * $request->so_luong;
        $gioHang->save();

        return response()->json(['message' => 'Số lượng sản phẩm đã được cập nhật'], 200);
    }

    public function deleteCart(Request $request)
    {
        $gioHang = gio_hang::where('ma_user', 1)->where('ma_bien_the', $request->ma_bien_the)->where('trang_thai', 'active')->first();
            
        $gioHang->delete();

        return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng!'], 200);
    }
}
