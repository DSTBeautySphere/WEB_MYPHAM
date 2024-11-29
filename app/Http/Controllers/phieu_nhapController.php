<?php

namespace App\Http\Controllers;

use App\Models\bien_the_san_pham;
use Illuminate\Http\Request;

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

}
