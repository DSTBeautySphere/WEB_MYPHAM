<?php

namespace App\Http\Controllers;

use App\Models\anh_san_pham;
use Illuminate\Http\Request;
use App\Models\san_pham;
use GuzzleHttp\Psr7\Response;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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
        return view('test');
    }

    public function uploadImage(Request $request)
    {
        // if ($request->hasFile('images')) {
        //     $imageUrls = []; // Mảng để lưu URL của các hình ảnh
    
        //     foreach ($request->file('images') as $file) {
        //         try {
        //             // Tải từng hình ảnh lên Cloudinary
        //             $result = Cloudinary::upload($file->getRealPath(), [
        //                 'folder' => 'PJ_MYPHAM/SanPham',
        //             ]);
    
        //             // Lấy URL của ảnh đã tải lên và thêm vào mảng
        //             $imageUrls[] = $result->getSecurePath();
        //         } catch (\Exception $e) {
        //             // Xử lý lỗi nếu có
        //             $error = "Lỗi: " . $e->getMessage();
        //             return view('test', ['success' => $error]);
        //         }
        //     }
    
        //     // Thông báo thành công và gửi mảng URL của các hình ảnh đến view
        //     $success = "Thành Công! Tất cả ảnh đã được tải lên thành công.";
        //     return view('test', ['success' => $success, 'imageUrls' => $imageUrls]);
        // }
    
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
                        'url_anh' => $result->getSecurePath(),  // Dùng getUrl() thay vì getSecureUrl()
                        'la_anh_chinh' => $bien === 0 ? 1 : 0,  // Đặt ảnh đầu tiên là ảnh chính
                    ]);
    
                    $bien++; // Tăng chỉ số để kiểm tra ảnh chính
    
                    // Lấy URL của ảnh đã tải lên và thêm vào mảng
                    // $imageUrls[] = $result->getSecurePath();  // Dùng getUrl() thay vì getSecureUrl()
                } catch (\Exception $e) {
                    // Xử lý lỗi nếu có
                    $error = "Lỗi: " . $e->getMessage();
                    return response()->json(['error' => 'Tệp không hợp lệ']);
                }
            }
        }
    
        // Nếu không có file nào được tải lên
        $success = "Không có ảnh nào được chọn!";
        return view('test', ['success' => $success]);
    }
    
}
