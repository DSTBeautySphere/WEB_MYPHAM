<?php

namespace App\Http\Controllers;

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
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            try {
                // Đảm bảo Cloudinary đã được cấu hình đúng trong .env và config/cloudinary.php
                // Sử dụng phương thức 'upload' từ Cloudinary để tải ảnh lên
    
                $result = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'PJ_MYPHAM/SanPham',
                ]);
    
                // Lấy đường dẫn HTTPS của ảnh sau khi tải lên
                $imageUrl = $result['secure_url']; 
    
                // Thông báo thành công
                $success = "Thành Công! Ảnh đã được tải lên thành công.";
                return view('test', ['success' => $success, 'imageUrl' => $imageUrl]);
            } catch (\Exception $e) {
                // Xử lý lỗi nếu có
                $error = "Lỗi: " . $e->getMessage();
                return view('test', ['success' => $error]);
            }
        }
    
        // Nếu không có file được tải lên
        $success = "Không có ảnh được chọn!";
        return view('test', ['success' => $success]);
    }
    
}
