<?php

namespace App\Http\Controllers;

use App\Models\loai_nhom_tuy_chon;
use App\Models\loai_san_pham;
use App\Models\nhom_tuy_chon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class loai_san_phamController extends Controller
{
    //
    public function loadLoaiSanPham(Request $request)
    {
        
        $request->validate([
            'ma_dong_san_pham' => 'required|integer|exists:dong_san_pham,ma_dong_san_pham',
        ]);

        try {
            // Tìm loại sản phẩm theo dòng sản phẩm
            $loaiSanPhamList = loai_san_pham::where('ma_dong_san_pham', $request->ma_dong_san_pham)->get();

            // Kiểm tra nếu không tìm thấy loại sản phẩm
            if ($loaiSanPhamList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại sản phẩm nào cho dòng sản phẩm này.'
                ], 404);
            }

            
            return response()->json([
                'success' => true,
                'data' => $loaiSanPhamList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi load loại sản phẩm: ' . $e->getMessage()
            ]);
        }
    }
    public function themLoaiSanPham(Request $request){
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'ma_dong_san_pham' => 'required|integer|exists:dong_san_pham,ma_dong_san_pham',
            'ten_loai_san_pham' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'nhom_tuy_chon' => 'required|array', // mảng các mã nhóm tùy chọn để thêm vào loai_nhom_tuy_chon
            'nhom_tuy_chon.*' => 'integer|exists:nhom_tuy_chon,ma_nhom_tuy_chon',
        ]);

        try {
            // Thêm loại sản phẩm mới vào cơ sở dữ liệu
            $loaiSanPham = loai_san_pham::create([
                'ma_dong_san_pham' => $request->ma_dong_san_pham,
                'ten_loai_san_pham' => $request->ten_loai_san_pham,
                'mo_ta' => $request->mo_ta,
            ]);

            // Thêm các bản ghi vào bảng loai_nhom_tuy_chon dựa trên nhom_tuy_chon trong request
            foreach ($request->nhom_tuy_chon as $maNhomTuyChon) {
                loai_nhom_tuy_chon::create([
                    'ma_nhom_tuy_chon' => $maNhomTuyChon,
                    'ma_loai_san_pham' => $loaiSanPham->ma_loai_san_pham,
                ]);
            }

            // Trả về phản hồi thành công
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Loại sản phẩm và loại nhóm tùy chọn đã được thêm thành công.',
            //     'data' => $loaiSanPham
            // ]);
            return redirect()->back();
        } catch (\Exception $e) {
            // Trả về phản hồi lỗi nếu có vấn đề
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm loại sản phẩm và loại nhóm tùy chọn: ' . $e->getMessage()
            ]);
        }
    }

    public function xoaLoaiSanPham(Request $request) {
        
        $request->validate([
            'ma_loai_san_pham' => 'required|integer|exists:loai_san_pham,ma_loai_san_pham'
        ]);
        try{
            $maLoaiSanPham= $request->ma_loai_san_pham;
            $loaiSanPham= loai_san_pham::findOrFail($maLoaiSanPham);

            loai_nhom_tuy_chon::where('ma_loai_san_pham',$maLoaiSanPham)->delete();

            $loaiSanPham->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa loại sản phẩm thành công.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi xóa loại sản phẩm: ' . $e->getMessage()]);
        }
    }

    public function suaLoaiSanPham(Request $request){

        $request->validate([
            'ma_loai_san_pham' => 'required|integer|exists:loai_san_pham,ma_loai_san_pham',
            'ma_dong_san_pham' => 'required|integer|exists:dong_san_pham,ma_dong_san_pham',
            'ten_loai_san_pham' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'nhom_tuy_chon' => 'array',
            'nhom_tuy_chon.*' => 'integer|exists:nhom_tuy_chon,ma_nhom_tuy_chon'
        ]);

        try {
            // Lấy ma_loai_san_pham từ request và tìm loại sản phẩm tương ứng
            $maLoaiSanPham = $request->ma_loai_san_pham;
            $loaiSanPham = loai_san_pham::findOrFail($maLoaiSanPham);
    
            // Cập nhật thông tin loại sản phẩm
            $loaiSanPham->update([
                'ma_dong_san_pham' => $request->ma_dong_san_pham,
                'ten_loai_san_pham' => $request->ten_loai_san_pham,
                'mo_ta' => $request->mo_ta
            ]);
    
            // Xóa các `loai_nhom_tuy_chon` hiện tại
            loai_nhom_tuy_chon::where('ma_loai_san_pham', $maLoaiSanPham)->delete();
    
            // Thêm các bản ghi mới vào bảng `loai_nhom_tuy_chon` nếu có
            if ($request->has('nhom_tuy_chon')) {
                foreach ($request->nhom_tuy_chon as $maNhomTuyChon) {
                    loai_nhom_tuy_chon::create([
                        'ma_loai_san_pham' => $maLoaiSanPham,
                        'ma_nhom_tuy_chon' => $maNhomTuyChon
                    ]);
                }
            }
    
            // return response()->json(['success' => true, 'message' => 'Đã cập nhật loại sản phẩm thành công.']);
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi cập nhật loại sản phẩm: ' . $e->getMessage()]);
        }
    }

    public function layNhomTuyChonTheoLoai(Request $request)
    {
        $loaiSanPham=loai_san_pham::find($request->ma_loai_san_pham);
        $tuyChonTheoLoai=$loaiSanPham->nhom_tuy_chon()->with('tuy_chon')->get();
        return Response()->json($tuyChonTheoLoai);
    }

    public function layLoaiSanPham()
    {
        $loaiSanPham=loai_san_pham::all();
        return Response()->json($loaiSanPham);
    }

    
}
