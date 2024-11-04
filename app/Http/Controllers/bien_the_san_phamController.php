<?php

namespace App\Http\Controllers;

use App\Models\bien_the_san_pham;
use App\Models\san_pham;
use Illuminate\Http\Request;

class bien_the_san_phamController extends Controller
{
    
    public function loadBienTheSanPham(Request $request)
    {
       
        $request->validate([
            'ma_san_pham' => 'required|integer|exists:san_pham,ma_san_pham',
        ]);

        try {
           
            $bienTheList = bien_the_san_pham::where('ma_san_pham', $request->ma_san_pham)->get();

            
            if ($bienTheList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có biến thể nào cho sản phẩm này.'
                ]);
            }

          
            return response()->json([
                'success' => true,
                'data' => $bienTheList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tải biến thể: ' . $e->getMessage()
            ]);
        }
    }


    public function themBienTheSanPham(Request $request)
    {
        
        $request->validate([
            'ma_san_pham' => 'required|integer|exists:san_pham,ma_san_pham',
            'mau_sac' => 'required|string|max:50',
            'loai_da' => 'required|string|max:50',
            'dung_tich' => 'required|numeric|min:0',
            'so_luong_ton_kho' => 'required|integer|min:0',
            'gia_ban' => 'required|numeric|min:0',
        ]);

        try {
          
            $sanPham = san_pham::find($request->ma_san_pham);
            
            // if (!$sanPham) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Sản phẩm không tồn tại.'
            //     ], 404);
            // }

           
            $bienThe = bien_the_san_pham::create([
                'ma_san_pham' => $sanPham->ma_san_pham,
                'mau_sac' => $request->mau_sac,
                'loai_da' => $request->loai_da,
                'dung_tich' => $request->dung_tich,
                'so_luong_ton_kho' => $request->so_luong_ton_kho,
                'gia_ban' => $request->gia_ban,
            ]);

            
            return response()->json([
                'success' => true,
                'message' => 'Biến thể đã được thêm thành công.',
                'data' => $bienThe
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm biến thể: ' . $e->getMessage()
            ]);
        }
    }

    public function suaBienTheSanPham(Request $request)
    {
        
        $request->validate([
            'mau_sac' => 'required|string|max:50',
            'loai_da' => 'required|string|max:50',
            'dung_tich' => 'required|numeric|min:0',
            'so_luong_ton_kho' => 'required|integer|min:0',
            'gia_ban' => 'required|numeric|min:0',
        ]);

        try {
           
            $bienThe = bien_the_san_pham::find($request->ma_bien_the);

           
            if (!$bienThe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Biến thể không tồn tại.'
                ], 404);
            }

           
            $bienThe->update([
                'mau_sac' => $request->mau_sac,
                'loai_da' => $request->loai_da,
                'dung_tich' => $request->dung_tich,
                'so_luong_ton_kho' => $request->so_luong_ton_kho,
                'gia_ban' => $request->gia_ban,
            ]);

            // Trả về phản hồi JSON
            return response()->json([
                'success' => true,
                'message' => 'Biến thể đã được cập nhật thành công.',
                'data' => $bienThe
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật biến thể: ' . $e->getMessage()
            ]);
        }
    }

    public function xoaBienTheSanPham(Request $request)
    {
       
       

        try {
            
            $bienThe = bien_the_san_pham::find($request->ma_bien_the);

            // // Kiểm tra nếu biến thể không tồn tại
            // if (!$bienThe) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Biến thể không tồn tại.'
            //     ], 404);
            // }

           
            $bienThe->delete();

           
            return response()->json([
                'success' => true,
                'message' => 'Biến thể đã được xóa thành công.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa biến thể: ' . $e->getMessage()
            ]);
        }
    }

}
