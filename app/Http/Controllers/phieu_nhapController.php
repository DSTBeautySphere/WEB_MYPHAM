<?php

namespace App\Http\Controllers;

use App\Models\bien_the_san_pham;
use App\Models\chi_tiet_phieu_nhap;
use App\Models\phieu_nhap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class phieu_nhapController extends Controller
{
    //
    public function showPhieuNhap()
    {
        return view('nhapkho.quan-ly-nhap-kho');
    }

    public function laySanPhamTheoNhaCC(Request $request)
    {
        $maNhaCungCap = $request->input('ma_nha_cung_cap');

        if ($maNhaCungCap) {
            $bienTheSanPhams = bien_the_san_pham::with(['san_pham.anh_san_pham'])
                ->where('so_luong_ton_kho', '>', 0)
                ->whereHas('san_pham', function ($query) use ($maNhaCungCap) {
                    $query->where('ma_nha_cung_cap', $maNhaCungCap);
                })
                ->get()
                ->map(function ($bienThe) {
                    return [
                        'ma_bien_the' => $bienThe->ma_bien_the,
                        'mau_sac' => $bienThe->mau_sac,
                        'loai_da' => $bienThe->loai_da,
                        'dung_tich' => $bienThe->dung_tich,
                        'so_luong_ton_kho' => $bienThe->so_luong_ton_kho,
                        'gia_ban' => $bienThe->gia_ban,
                        'ten_san_pham' => $bienThe->san_pham->ten_san_pham ?? 'Không xác định',
                        'hinh_anh' => $bienThe->san_pham->anh_san_pham->pluck('url_anh')->toArray()
                    ];
                });

            if ($bienTheSanPhams->isEmpty()) {
                return response()->json(['message' => 'Không có sản phẩm nào thỏa mãn!'], 404);
            }

            return response()->json($bienTheSanPhams, 200);
        } else {
            return response()->json(['message' => 'Nhà cung cấp không hợp lệ!'], 400);
        }
    }
    public function themPhieuNhap(Request $request)
    {
    
        try {
            Log::info('Dữ liệu phiếu nhập:', $request->all());
            // Lưu thông tin phiếu nhập
            $phieuNhap = phieu_nhap::create([
                'ma_nha_cung_cap' => $request->input('ma_nha_cung_cap'),
                'ngay_nhap' => now(),
                'tong_so_luong' => $request->input('tong_so_luong'),
                'tong_gia_tri' => $request->input('tong_gia_tri'),
                'ghi_chu' => $request->input('ghi_chu'),
                'trang_thai' => 1, // Mặc định trạng thái phiếu nhập là 1 (Đang xử lý)
            ]);
    
            // Lưu chi tiết phiếu nhập
            $chiTietPhieuNhap = $request->input('chi_tiet_phieu_nhap');
            foreach ($chiTietPhieuNhap as $chiTiet) {
                chi_tiet_phieu_nhap::create([
                    'ma_phieu_nhap' => $phieuNhap->ma_phieu_nhap,
                    'ma_bien_the' => $chiTiet['maBienThe'],
                    'so_luong' => $chiTiet['soLuong'],
                    'gia_nhap' => $chiTiet['giaNhap'],
                ]);
                // Cập nhật giá bán trong bảng bien_the_san_pham
                $bienTheSanPham = bien_the_san_pham::where('ma_bien_the', $chiTiet['maBienThe'])->first();
                if ($bienTheSanPham) {
                    // Tính giá bán (Giả sử giá bán được tính theo công thức đã được định nghĩa trước đó)
                    $giaBan = $chiTiet['giaBan'] ;// Sử dụng thông tin từ chi tiết để tính giá bán

                    // Cập nhật giá bán
                    $bienTheSanPham->update([
                        'gia_ban' => $giaBan,
                    ]);
                }
            }
    
          
    
            return response()->json([
                'success' => true,
                'message' => 'Thêm phiếu nhập thành công!',
            ]);
        } catch (\Exception $e) {
           
    
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
