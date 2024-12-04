<?php

namespace App\Http\Controllers;

use App\Models\voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class voucherController extends Controller
{
    //
    public function showVoucher(){
        $vouchers= voucher::all();
         // Lọc và xóa các voucher đã hết hạn
        foreach ($vouchers as $voucher) {
            if ($voucher->ngay_ket_thuc < now()) {
                $voucher->delete();
            }
        }

        // Lấy lại danh sách voucher sau khi xóa
        $vouchers = Voucher::all();
        return view('voucher.voucher-quan-ly', compact('vouchers'));
    }
    public function showVoucherForm(){
        $vouchers= voucher::all();
         // Lọc và xóa các voucher đã hết hạn
        foreach ($vouchers as $voucher) {
            if ($voucher->ngay_ket_thuc < now()) {
                $voucher->delete();
            }
        }

        // Lấy lại danh sách voucher sau khi xóa
        $vouchers = Voucher::all();
        return response()->json( $vouchers,200);
    }
    public function themVoucherFrom(Request $request)
    {
        // Validate dữ liệu nhận từ form
        $validated = $request->validate([
           
            'loai_giam_gia' => 'required|string|max:255',
            'muc_giam_gia' => 'required|numeric',
            'gia_tri_dieu_kien' => 'required|numeric',
            'giam_gia_toi_da' => 'required|numeric',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'so_luong' => 'required|integer',
        ]);

        $code_voucher = $request->input('code_voucher');
        if (empty($code_voucher)) {
            $code_voucher = 'VOUCHER-' . Str::random(8);  // Tạo mã voucher ngẫu nhiên, có tiền tố 'VOUCHER-'
        }

        // Tạo voucher mới
        $voucher = new Voucher();
        $voucher->code_voucher = $code_voucher;
        $voucher->loai_giam_gia = $request->input('loai_giam_gia');
        $voucher->muc_giam_gia = $request->input('muc_giam_gia');
        $voucher->gia_tri_dieu_kien = $request->input('gia_tri_dieu_kien');
        $voucher->giam_gia_toi_da = $request->input('giam_gia_toi_da');
        $voucher->ngay_bat_dau = $request->input('ngay_bat_dau');
        $voucher->ngay_ket_thuc = $request->input('ngay_ket_thuc');
        $voucher->so_luong = $request->input('so_luong');
        $voucher->trang_thai=1;

        // Lưu voucher vào cơ sở dữ liệu
        $voucher->save();

        // Redirect hoặc thông báo thành công
        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công!',
        ]);
    }
    public function xoaVoucherFrom($id)
    {
        try {
            
            $voucher = Voucher::findOrFail($id);
            
          
           
            $voucher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Thêm thành công!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thành công!',
            ]);
        }
    }
    public function themVoucher(Request $request)
    {
        // Validate dữ liệu nhận từ form
        $validated = $request->validate([
           
            'loai_giam_gia' => 'required|string|max:255',
            'muc_giam_gia' => 'required|numeric',
            'gia_tri_dieu_kien' => 'required|numeric',
            'giam_gia_toi_da' => 'required|numeric',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'so_luong' => 'required|integer',
        ]);

        $code_voucher = $request->input('code_voucher');
        if (empty($code_voucher)) {
            $code_voucher = 'VOUCHER-' . Str::random(8);  // Tạo mã voucher ngẫu nhiên, có tiền tố 'VOUCHER-'
        }

        // Tạo voucher mới
        $voucher = new Voucher();
        $voucher->code_voucher = $code_voucher;
        $voucher->loai_giam_gia = $request->input('loai_giam_gia');
        $voucher->muc_giam_gia = $request->input('muc_giam_gia');
        $voucher->gia_tri_dieu_kien = $request->input('gia_tri_dieu_kien');
        $voucher->giam_gia_toi_da = $request->input('giam_gia_toi_da');
        $voucher->ngay_bat_dau = $request->input('ngay_bat_dau');
        $voucher->ngay_ket_thuc = $request->input('ngay_ket_thuc');
        $voucher->so_luong = $request->input('so_luong');
        $voucher->trang_thai=1;

        // Lưu voucher vào cơ sở dữ liệu
        $voucher->save();

        // Redirect hoặc thông báo thành công
        return redirect()->route('voucher.index')->with('success', 'Voucher đã được thêm thành công!');
    }

    public function xoaVoucher($id)
    {
        try {
            
            $voucher = Voucher::findOrFail($id);
            
          
           
            $voucher->delete();

            return redirect()->route('voucher.index')->with('success', 'Voucher đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('voucher.index')->with('error', 'Có lỗi xảy ra khi xóa voucher.');
        }
    }

}
