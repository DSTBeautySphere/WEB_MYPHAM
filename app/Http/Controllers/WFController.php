<?php

namespace App\Http\Controllers;

use App\Models\anh_san_pham;
use App\Models\bien_the_san_pham;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WFController extends Controller
{
    //
    public function layAnhTheoSanPham(Request $request)
    {
        $anhSanPham=anh_san_pham::where('ma_san_pham',$request->ma_san_pham)->get();
        return response()->json($anhSanPham);
    }

    // public function themAnhSanPham(Request $request)
    // {
    //     try {
           
    //         $anhSanPham = anh_san_pham::create([
    //             'ma_san_pham' => $request->ma_san_pham,
    //             'url_anh' => $request->url_anh,
    //             'la_anh_chinh' => $request->la_anh_chinh,
    //         ]);

    //         return response()->json([
    //             'message' => 'Thêm ảnh sản phẩm thành công!',
    //             'data' => $anhSanPham,
    //         ], 201);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Đã xảy ra lỗi khi thêm ảnh sản phẩm.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
   
    // }

    public function suaAnhSanPham(Request $request)
    {
        try 
        {
            $bien = 0;

            // Kiểm tra nếu có ảnh được chọn
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    try {
                        // Lấy dữ liệu ảnh cũ (nếu có)
                        if ($request->has('ds_anhcu')) {
                            $anhcuData = json_decode($request->ds_anhcu, true);

                            if (isset($anhcuData[$bien]['ma_anh_san_pham'])) {
                                $anhcu = anh_san_pham::find($anhcuData[$bien]['ma_anh_san_pham']);
                                if ($anhcu) {
                                    // Lấy public_id từ URL ảnh cũ
                                    $publicId = basename($anhcu->url_anh, '.' . pathinfo($anhcu->url_anh, PATHINFO_EXTENSION));

                                    // Xóa ảnh cũ trên Cloudinary
                                    Cloudinary::destroy('PJ_MYPHAM/SanPham/' . $publicId);
                                } else {
                                    return response()->json([
                                        'message' => "Ảnh cũ không tồn tại với mã: {$anhcuData[$bien]['ma_anh_san_pham']}",
                                    ], 404);
                                }
                            } else {
                                return response()->json([
                                    'message' => 'Dữ liệu ảnh cũ không đầy đủ.',
                                ], 400);
                            }
                        }

                        // Tải ảnh mới lên Cloudinary
                        $result = Cloudinary::upload($file->getRealPath(), [
                            'folder' => 'PJ_MYPHAM/SanPham',
                        ]);

                        // Cập nhật URL ảnh mới vào cơ sở dữ liệu
                        if (isset($anhcu)) {
                            $anhcu->url_anh = $result->getSecurePath();
                            $anhcu->save();
                        }

                        $bien++; // Tăng biến khi đã xử lý ảnh
                    } catch (\Exception $e) {
                        return response()->json([
                            'message' => 'Có lỗi xảy ra khi tải ảnh.',
                            'error' => $e->getMessage(),
                        ], 500);
                    }
                }
            } else {
                return response()->json([
                    'message' => 'Không có ảnh nào được chọn để tải lên.',
                ], 400);
            }

            return response()->json([
                'message' => 'Cập nhật ảnh sản phẩm thành công!',
            ], 201);
        } catch (\Exception $e) 
        {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi thêm ảnh.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function layBienTheTheoSanPham(Request $request)
    {
        $bienThe=bien_the_san_pham::where('ma_san_pham',$request->ma_san_pham)->get();
        return response()->json($bienThe);
    }

    public function themAnhSanPham(Request $request)
    {
        // Kiểm tra file ảnh
        if (!$request->hasFile('images')) {
            return response()->json(['message' => 'Vui lòng chọn ảnh để tải lên'], 400);
        }

        try {
            $maSanPham = $request->input('ma_san_pham');
            
            // Kiểm tra xem đã có ảnh chính cho sản phẩm chưa
            $hasMainImage = anh_san_pham::where('ma_san_pham', $maSanPham)
                ->where('la_anh_chinh', 1)
                ->exists();

            $uploadedImages = collect($request->file('images'))->map(function ($image, $index) use ($request, $maSanPham, $hasMainImage) {
                if ($image->isValid()) {
                    $result = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'PJ_MYPHAM/SanPham',
                    ]);

                    return anh_san_pham::create([
                        'ma_san_pham' => $maSanPham,
                        'url_anh' => $result->getSecurePath(),
                        'la_anh_chinh' => (!$hasMainImage && $index === 0) ? 1 : 0, // Ảnh đầu tiên làm ảnh chính nếu chưa có
                    ]);
                }
            })->filter(); // Loại bỏ phần tử null nếu file không hợp lệ

            return response()->json(['message' => 'Tải ảnh thành công', 'data' => $uploadedImages], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function xoaAnhSanPham(Request $request)
    {
        try {
            // Kiểm tra ID ảnh
            $maAnhSanPham = $request->input('ma_anh_san_pham');
            if (!$maAnhSanPham) {
                return response()->json(['message' => 'Vui lòng cung cấp mã ảnh'], 400);
            }

            // Tìm ảnh trong cơ sở dữ liệu
            $anhSanPham = anh_san_pham::find($maAnhSanPham);
            if (!$anhSanPham) {
                return response()->json(['message' => 'Ảnh không tồn tại'], 404);
            }

            // Xóa ảnh trên Cloudinary
            $urlAnh = $anhSanPham->url_anh;
            $publicId = basename($urlAnh, '.' . pathinfo($urlAnh, PATHINFO_EXTENSION)); // Tách public_id từ URL ảnh
            $deleteResult = Cloudinary::destroy('PJ_MYPHAM/SanPham/'.$publicId);

            if (isset($deleteResult['result']) && $deleteResult['result'] === 'ok') {
                // Xóa ảnh khỏi cơ sở dữ liệu
                $anhSanPham->delete();
                return response()->json(['message' => 'Xóa ảnh thành công'], 200);
            } else {
                return response()->json(['message' => 'Xóa ảnh trên Cloudinary thất bại'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
        }
    }




    


    

    

}
