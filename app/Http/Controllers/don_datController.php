<?php

namespace App\Http\Controllers;

use App\Models\chi_tiet_don_dat;
use App\Models\chi_tiet_gio_hang;
use Illuminate\Http\Request;
use App\Models\don_dat;
use App\Models\gio_hang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class don_datController extends Controller
{
    //
    public function themDonDat(Request $request)
    {
        // $donDat = don_dat::create([
        //     'ma_user' => 1,
        //     'ngay_dat' => $request->ngay_dat,
        //     'tong_tien' => $request->tong_tien,
        //     'trang_thai' => $request->trang_thai,
        // ]);

        // $giohang = gio_hang::where('ma_user', 1)->where('trang_thai', 'active')->first();

        // if($giohang){
        //     $chitietgiohang = chi_tiet_gio_hang::where('ma_gio_hang', $giohang->ma_gio_hang)->get();
        //     foreach($chitietgiohang as $ctgh){
        //         chi_tiet_don_dat::create([
        //             'ma_don_dat' => $donDat->ma_don_dat,
        //             'ma_bien_the' => $ctgh->ma_bien_the,
        //             'so_luong' => $ctgh->so_luong,
        //             'gia_ban' => $ctgh->gia_ban
        //         ]);

        //         $ctgh->delete();
        //     }
        //     $giohang->delete();
        // }


        // return response()->json(['message' => 'Đơn hàng đã đặt thành công!'], 200);
    }


    // Hàm để xem chi tiết đơn hàng
    public function show($id)
    {
        // Tìm đơn hàng theo ma_don_dat
        $donDat = don_dat::with(['chi_tiet_don_dat.bien_the_san_pham.san_pham'])->findOrFail($id);

        // Trả về view với chi tiết đơn hàng
        return response()->json($donDat);
    }

    public function loc_du_lieuDH(Request $request)
    {
        // Lấy các thông số từ request
        $date = $request->input('date');
        $status = $request->input('status');
        $price = $request->input('price');

        // Query ban đầu
        $query = don_dat::query();

        // Lọc theo ngày đặt hàng (nếu có)
        if (!empty($date)) {
            $query->whereDate('ngay_dat', '=', $date);
        }

        // Lọc theo trạng thái (nếu có)
        if (!empty($status) && $status !== 'all') {
            $query->where('trang_thai_don_dat', $status);
        }
        if (!empty($status) && $status !== 'all') {
            $query->where('trang_thai_giao_hang', $status);
        }
        // Lọc theo giá lớn hơn (nếu có)
        if (!empty($price)) {
            $query->where('tong_tien_cuoi_cung', '>', $price);
        }

        // Lấy danh sách đã lọc
        $orders = $query->get();

        // Trả về JSON để AJAX xử lý
        return response()->json($orders);
    }

    public function capNhatTrangThaiGH(Request $request){
        try{
            $donDat= don_dat::find($request->input('ma_don_dat'));
            if($donDat){
                $donDat->trang_thai_don_dat = $request->input('trang_thai_giao_hang');
                $donDat->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật trạng thái giao hàng thành công!',
                ]);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ]);
        }
    }
    public function exportPdf(Request $request)
    {
        try 
        {
            Log::info('Some message', $request->all());
            // Lấy danh sách đơn đặt hàng được chọn
            $selectedDonDat = $request->input('don_dat'); 

            if (is_string($selectedDonDat)) {
                $selectedDonDat = explode(',', $selectedDonDat);
            }

            if (empty($selectedDonDat)) {
                return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một đơn đặt hàng!');
            }

            // Lấy dữ liệu đơn đặt hàng cùng các chi tiết liên quan
            $donDatList = don_dat::with('chi_tiet_don_dat.bien_the_san_pham.san_pham', 'user')
                                ->whereIn('ma_don_dat', $selectedDonDat)
                                ->get();

            Log::debug('Retrieved orders: ', $donDatList->toArray());

            // Tạo file PDF từ view
            $pdfPath = public_path('pdf/don_dat.pdf');
            $pdf = Pdf::loadView('donhang.pdf', compact('donDatList'));
            $pdf->save($pdfPath);

            // Trả về đường dẫn PDF cho client
            return response()->json(['pdfPath' => asset('pdf/don_dat.pdf')], 200);
        } 
        catch (\Exception $e) 
        {
            // Ghi log lỗi nếu có vấn đề
            Log::error('Lỗi xuất PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi xuất PDF'], 500);
        }
    }


}
