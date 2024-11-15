<?php

namespace App\Http\Controllers;

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
        $gioHang = gio_hang::where('ma_user', $userId)->with('user')->first();

        // Kiểm tra nếu giỏ hàng không tồn tại
        if (!$gioHang) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng của bạn trống.'
            ]);
        }

        // Lấy chi tiết giỏ hàng và load quan hệ sản phẩm -> biến thể -> ảnh sản phẩm
        $chiTietGH = chi_tiet_gio_hang::where('ma_gio_hang', $gioHang->ma_gio_hang)
                                      ->with([
                                          'bien_the_san_pham.san_pham.anh_san_pham',  // ảnh sản phẩm
                                          'bien_the_san_pham.san_pham'  // sản phẩm
                                      ])
                                      ->get();

        // Chuyển đổi chi tiết giỏ hàng thành cấu trúc mong muốn
        $result = $chiTietGH->map(function ($chiTiet) {
            return [
                'ma_gio_hang' => $chiTiet->ma_gio_hang,
                'so_luong' => $chiTiet->so_luong,
                'gia_ban' => $chiTiet->gia_ban,
                'bien_the_san_pham' => $chiTiet->bien_the_san_pham 
                    ? [
                        // Nếu bien_the_san_pham là một đối tượng, xử lý trực tiếp
                        'ma_bien_the' => $chiTiet->bien_the_san_pham->ma_bien_the,
                        'ten_san_pham' => $chiTiet->bien_the_san_pham->san_pham->ten_san_pham,
                        'mau_sac' => $chiTiet->bien_the_san_pham->mau_sac,
                        'loai_da' => $chiTiet->bien_the_san_pham->loai_da,
                        'dung_tich' => $chiTiet->bien_the_san_pham->dung_tich,
                        'gia_ban' => $chiTiet->bien_the_san_pham->gia_ban,
                        // Lấy URL ảnh sản phẩm
                        'anh_san_pham' => $chiTiet->bien_the_san_pham->san_pham->anh_san_pham->pluck('url_anh')
                    ] 
                    : [] // Nếu không có biến thể, trả về mảng rỗng
            ];
        });

        // Trả về kết quả
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách chi tiết giỏ hàng thành công.',
            'gio_hang' => $gioHang,
            'chi_tiet_gio_hang' => $result
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

        
        $gioHang = gio_hang::where('ma_user', $userId)->where('trang_thai', 'active')->first();

       
        if (!$gioHang) {
            $gioHang = gio_hang::create([

                'ma_user' => $userId,
                'ma_bien_the'=> $request->ma_bien_the,
                'so_luong'=> $request->so_luong,
                'gia_ban'=> $request->gia_ban,
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
