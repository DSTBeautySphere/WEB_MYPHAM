<?php

namespace App\Http\Controllers;

use App\Models\danh_gia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class danh_giaController extends Controller
{
    //
    public function guiDanhGia(Request $request)
    {
        $request->validate([
            'ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'noi_dung' => 'required|string|max:1000',
            'so_sao' => 'required|integer|min:1|max:5',
        ]);

        try {
            $danhGia = danh_gia::create([
                'ma_san_pham' => $request->ma_san_pham,
                'ma_user' => Auth::id(), // Lấy ID người dùng hiện tại $request->ma_user,
                'noi_dung' => $request->noi_dung,
                'so_sao' => $request->so_sao,
                'ngay_danh_gia' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được gửi thành công.',
                'data' => $danhGia
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gửi đánh giá: ' . $e->getMessage()
            ]);
        }
    }
}
