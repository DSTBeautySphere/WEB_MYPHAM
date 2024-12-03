<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\bien_the_san_pham;
use App\Models\chi_tiet_don_dat;
use App\Models\don_dat;
use App\Models\gio_hang;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VnPayController extends Controller
{
    public function createPayment(Request $request)
    {
        $vnp_TmnCode = "PJCNXYWL";
        $vnp_HashSecret = "0NVE7YL3N6PKEJMJ8GYFPXDVQN5NABTE";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:5173/vnpay-return";
        $vnp_Amount = $request->amount;
        $vnp_Locale = "vn";
        $vnp_BankCode = $request->bankCode;
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $order = don_dat::create([
            "ma_user" => $request->userId,
            "ngay_dat" => now(),
            "tong_tien_ban_dau" => $request->amountbf,
            "giam_gia"=>$request->discount,
            "tong_tien_cuoi_cung" => $vnp_Amount,
            "so_dien_thoai" => $request->phone,
            "dia_chi_giao_hang" => $request->address,
            "ghi_chu" => $request->note,
            "ngay_du_kien_giao"=>now(),
            "ngay_thanh_toan"=>now(),
            "phuong_thuc_thanh_toan" => "CARD",
            "trang_thai_thanh_toan" => "CHO_THANH_TOAN",
            "trang_thai_don_dat" => "Chờ xác nhận"
        ]);

        $vnp_TxnRef = $order->ma_don_dat;

        $cart = gio_hang::where('ma_user', $request->userId)->get();

        foreach ($cart as $item) {

            $variant = bien_the_san_pham::find($item->ma_bien_the);
            if ($variant->so_luong_ton_kho < $item->so_luong) {
                return response()->json([
                    'status' => 'error'
                   
                ]);
            }
            chi_tiet_don_dat::create([
                "ma_don_dat" => $order->ma_don_dat,
                "ma_bien_the" => $item->ma_bien_the,
                "so_luong" => $item->so_luong,
                "gia_ban" => $item->gia_ban
            ]);
            $variant->so_luong_ton_kho -= $item->so_luong;
            $variant->save();
            $item->delete();
        }
 
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount* 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toán đơn hàng",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return response()->json(['status' => 'success', 'url' => $vnp_Url]);
    }
}