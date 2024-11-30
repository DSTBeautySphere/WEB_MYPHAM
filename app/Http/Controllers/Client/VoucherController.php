<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\user_voucher;
use App\Models\voucher;
use Exception;


class VoucherController extends Controller
{
    public function getVouchers(Request $request)
{
    try {
        // Lấy ID người dùng từ request (nếu bạn truyền ma_user trong request)
        $userId = $request->ma_user; // Lấy ma_user từ request

        // Kiểm tra nếu ma_user không tồn tại trong request
        if (!$userId) {
            return response()->json(['error' => 'Ma_user không được cung cấp'], 400);
        }

        // Lấy danh sách voucher đang hoạt động mà người dùng chưa lưu
        $vouchers = voucher::where('trang_thai', "1")
            ->where('ngay_bat_dau', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->whereNotIn('ma_voucher', function ($query) use ($userId) {
                // Lấy danh sách các voucher mà người dùng đã lưu
                $query->select('ma_voucher')
                      ->from('user_voucher')
                      ->where('ma_user', $userId);
            })
            ->get();

        return response()->json($vouchers, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    

    public function storeVoucher(Request $request)
    {
        $userId = $request->ma_user;  
        $voucherId = $request->ma_voucher; 
        $voucher = Voucher::find($voucherId);
        if ($voucher && $voucher->so_luong > 0) {
            user_voucher::create([
                'ma_user' => $userId,
                'ma_voucher' => $voucherId,
            ]);
            $voucher->so_luong -= 1;
            $voucher->save();

            return response()->json([
                'message' => 'Voucher đã được lưu và số lượng đã được cập nhật.',
                'voucher' => $voucher
            ], 200);
        }
        return response()->json([
            'message' => 'Voucher không tồn tại hoặc đã hết số lượng.',
        ], 404);
    }
}