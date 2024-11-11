<?php

namespace App\Http\Controllers;

use App\Models\anh_san_pham;
use App\Models\bien_the_san_pham;
use App\Models\loai_san_pham;
use App\Models\nha_cung_cap;
use App\Models\san_pham;
use App\Models\tuy_chon;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AdminController extends Controller
{
    //
    public function index()
    {
        return view('sanpham.them-san-pham');
    }
    //them-san-pham
    public function showThemSanPham()
    {
        $loaiSanPham=loai_san_pham::all();
        $nhaCungCap=nha_cung_cap::all();
        $tuyChon=tuy_chon::all();
        return view('sanpham.them-san-pham',['loaiSanPham'=>$loaiSanPham,'nhaCungCap'=>$nhaCungCap,'tuyChon'=>$tuyChon]);
    }

    

    public function themSanPhamVaBienThe(Request $request)
    {
     

        try {
            // Kiểm tra dữ liệu đầu vào
            $validated = $request->validate([
                'ten_san_pham' => 'required|string|max:255',
                'ma_loai_san_pham' => 'required|string|exists:loai_san_pham,ma_loai_san_pham',
                'ma_nha_cung_cap' => 'required|string|exists:nha_cung_cap,ma_nha_cung_cap',
                'bien_the' => 'array',
                'bien_the.*.mau_sac' => 'nullable|string|max:100', 
                'bien_the.*.loai_da' => 'nullable|string|max:100',  
                'bien_the.*.dung_tich' => 'nullable|string|max:100',  // Chỉ yêu cầu là chuỗi
                'bien_the.*.so_luong_ton_kho' => 'nullable|integer|min:0',
                'bien_the.*.gia_ban' => 'nullable|numeric|min:0',
            ]);

            // Tạo sản phẩm mới bằng phương thức create
            $sanPham = san_pham::create([
                'ten_san_pham' => $request->ten_san_pham,
                'ma_loai_san_pham' => $request->ma_loai_san_pham,
                'ma_nha_cung_cap' => $request->ma_nha_cung_cap,
            ]);

            // Xử lý ảnh sản phẩm
            if ($request->has('anh_san_pham')) {
                $bien = 0;
                foreach ($request->anh_san_pham as $image) {
                    // Kiểm tra và tải ảnh lên Cloudinary
                    $upload = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'PJ_MYPHAM/SanPham/'
                    ]);

                    // Lưu thông tin ảnh vào cơ sở dữ liệu
                    anh_san_pham::create([
                        'ma_san_pham' => $sanPham->ma_san_pham,
                        'url_anh' => $upload->getSecureUrl(),
                        'la_anh_chinh' => $bien === 0 ? 1 : 0,  // Đặt ảnh đầu tiên là ảnh chính
                    ]);
                    $bien++; // Tăng chỉ số để kiểm tra ảnh chính
                }
            }

            // Thêm các biến thể sản phẩm
            if ($request->has('bien_the')) {
                foreach ($request->bien_the as $bienThe) {
                    // Lưu các biến thể sản phẩm vào cơ sở dữ liệu
                    bien_the_san_pham::create([
                        'ma_san_pham' => $sanPham->ma_san_pham,
                        'mau_sac' => $bienThe['mau_sac'] ?? null,
                        'loai_da' => $bienThe['loai_da'] ?? null,
                        'dung_tich' => $bienThe['dung_tich'] ?? null,  // giữ chuỗi dung_tich
                        'so_luong_ton_kho' => $bienThe['so_luong_ton_kho'] ?? null,
                        'gia_ban' => $bienThe['gia_ban'] ?? null,
                    ]);
                }
            }

            // Trả về thông báo thành công
            
            return response()->json(['message' => 'Thêm thành công']);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có và trả về thông báo lỗi chi tiết
            return response()->json([
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage(),
                
            ], 500);
         
        }
    }

    

    

    
}
