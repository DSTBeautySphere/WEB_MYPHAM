<?php

namespace App\Http\Controllers;

use App\Models\bien_the_san_pham;
use App\Models\chi_tiet_don_dat;
use App\Models\doi_tra;
use App\Models\don_dat;
use Illuminate\Http\Request;

class doi_traController extends Controller
{
    //
    public function danhSachDoiTra(){
        $doi_tra = doi_tra::with('bien_the_san_pham.san_pham')->paginate(5);
        return view('doitra.quan-ly-doi-tra',compact('doi_tra'));
    }

    public function acceptRequest($id)
    {
        try {
            // Lấy thông tin yêu cầu đổi trả
            $doi_tra = doi_tra::findOrFail($id);
    
            // Lấy thông tin đơn đặt liên kết với yêu cầu đổi trả
            $donDat = $doi_tra->don_dat; // Quan hệ belongsTo đã được định nghĩa
    
            // Cập nhật trạng thái yêu cầu đổi trả
            $doi_tra->trang_thai = 'Đã chấp nhận';
            $doi_tra->save();
    
            // Tạo một đơn đặt mới dựa trên thông tin từ đơn đặt cũ
            $newOrder = $donDat->replicate(); // Sao chép thông tin từ đơn đặt cũ
            $newOrder->ngay_dat = now(); // Cập nhật ngày đặt mới
            $newOrder->trang_thai_don_dat = 'Chờ lấy hàng'; // Trạng thái mặc định
            $newOrder->tong_tien_cuoi_cung = 0; // Đặt tổng tiền cuối cùng về 0
            $newOrder->save();
    
            // Tạo chi tiết đơn đặt hàng mới với mã biến thể từ đổi trả
            $chiTietDonDat = new chi_tiet_don_dat();
            $chiTietDonDat->ma_don_dat = $newOrder->ma_don_dat;
            $chiTietDonDat->ma_bien_the = $doi_tra->ma_bien_the;
            $chiTietDonDat->so_luong = 1; 
            $chiTietDonDat->gia_ban = bien_the_san_pham::find($doi_tra->ma_bien_the)->gia_ban; 
            $chiTietDonDat->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function rejectRequest($id)
    {
        try {
            // Lấy thông tin yêu cầu đổi trả
            $doi_tra = doi_tra::findOrFail($id);
            
            // Cập nhật trạng thái yêu cầu đổi trả
            $doi_tra->trang_thai = 'Đã từ chối'; // Cập nhật trạng thái thành "Đã từ chối"
            $doi_tra->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    
}
