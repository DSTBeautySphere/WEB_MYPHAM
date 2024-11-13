<?php

namespace App\Http\Controllers;

use App\Models\anh_san_pham;
use Illuminate\Http\Request;
use App\Models\san_pham;
use GuzzleHttp\Psr7\Response;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
class TestController extends Controller
{
    //
    public function tat_ca_san_pham()
    {
        $sanPhams = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])->get();
      
        return response()->json($sanPhams);
    }

    public function showForm()
    {
        return view('sanpham.quan-ly-san-pham');
    }

    public function uploadImage(Request $request)
{
    if ($request->hasFile('images')) {
        $bien = 0;
        $imageUrls = []; // Mảng để lưu URL của các hình ảnh

        foreach ($request->file('images') as $file) {
            try {
                // Tải từng hình ảnh lên Cloudinary
                $result = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'PJ_MYPHAM/SanPham',
                ]);

                // Lưu thông tin vào bảng anh_san_pham
                anh_san_pham::create([
                    'ma_san_pham' => '1',
                    'url_anh' => $result->getSecurePath(),
                    'la_anh_chinh' => $bien === 0 ? 1 : 0,  // Đặt ảnh đầu tiên là ảnh chính
                ]);

                $bien++; // Tăng chỉ số để kiểm tra ảnh chính
            } catch (\Exception $e) {
                // Ghi lại thông tin lỗi vào log với thông tin chi tiết
                Log::error('Lỗi tải ảnh lên Cloudinary: ' . $e->getMessage(), [
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'error' => $e->getMessage(),
                ]);

                // Trả về thông báo lỗi
                return response()->json(['error' => 'Tệp không hợp lệ']);
            }
        }
    }

    // Nếu không có file nào được tải lên
    $success = "Không có ảnh nào được chọn!";
    return view('test', ['success' => $success]);
}

    
}
