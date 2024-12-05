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


    public function showVoucherUser(Request $request)
{
    try {
        // Lấy ID người dùng từ request
        $userId = $request->input('ma_user');

        // Kiểm tra nếu không có userId
        if (!$userId) {
            return response()->json(['error' => 'Mã người dùng không được cung cấp'], 400);
        }

        // Lấy danh sách voucher đã lưu và thông tin chi tiết của voucher
        $vouchers = user_voucher::where('ma_user', $userId)
            ->with(['voucher' => function($query) {
                // Eager load thông tin voucher chi tiết với đầy đủ các trường
                $query->select(
                    'ma_voucher', 
                    'code_voucher', 
                    'ten_voucher', 
                    'muc_giam_gia', 
                    'loai_giam_gia', 
                    'gia_tri_dieu_kien', 
                    'giam_gia_toi_da', 
                    'so_luong', 
                    'ngay_bat_dau', 
                    'ngay_ket_thuc', 
                    'dieu_kien_ap_dung', 
                    'trang_thai'
                );
            }])
            ->get()
            ->map(function ($userVoucher) {
                // Chỉ lấy thông tin voucher chi tiết từ bảng voucher
                return [
                    'ma_voucher' => $userVoucher->voucher->ma_voucher,
                    'code_voucher' => $userVoucher->voucher->code_voucher,
                    'ten_voucher' => $userVoucher->voucher->ten_voucher,
                    'muc_giam_gia' => $userVoucher->voucher->muc_giam_gia,
                    'loai_giam_gia' => $userVoucher->voucher->loai_giam_gia,
                    'gia_tri_dieu_kien' => $userVoucher->voucher->gia_tri_dieu_kien,
                    'giam_gia_toi_da' => $userVoucher->voucher->giam_gia_toi_da,
                    'so_luong' => $userVoucher->voucher->so_luong,
                    'ngay_bat_dau' => $userVoucher->voucher->ngay_bat_dau,
                    'ngay_ket_thuc' => $userVoucher->voucher->ngay_ket_thuc,
                    'dieu_kien_ap_dung' => $userVoucher->voucher->dieu_kien_ap_dung,
                    'trang_thai' => $userVoucher->voucher->trang_thai,
                ];
            });

        // Trả về danh sách voucher đã lưu dưới dạng JSON
        return response()->json($vouchers, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
    }
}

public function deleteVoucherUser(Request $request)
{
    try {
        // Kiểm tra nếu ma_voucher và ma_user được cung cấp trong request
        if (!$request->has('ma_voucher') || !$request->has('ma_user')) {
            return response()->json(['error' => 'Mã voucher hoặc mã người dùng không được cung cấp'], 400);
        }

        // Tìm voucher của người dùng dựa trên ma_user và ma_voucher
        $voucher = user_voucher::where('ma_voucher', $request->ma_voucher)
                               ->where('ma_user', $request->ma_user)
                               ->first();

        // Kiểm tra nếu voucher không tồn tại
        if (!$voucher) {
            return response()->json(['error' => 'Voucher không tồn tại'], 404);
        }

        // Xóa voucher
        $voucher->delete();

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Voucher đã được xóa thành công'], 200);
    } catch (\Exception $e) {
        // Trả về phản hồi lỗi nếu có ngoại lệ
        return response()->json(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
    }
}




}