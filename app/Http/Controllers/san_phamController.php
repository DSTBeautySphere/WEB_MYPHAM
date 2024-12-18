<?php

namespace App\Http\Controllers;

use App\Models\anh_san_pham;
use App\Models\bien_the_san_pham;
use App\Models\chi_tiet_don_dat;
use App\Models\chi_tiet_mo_ta;
use Illuminate\Http\Request;
use App\Models\san_pham;
use App\Models\loai_san_pham;
use App\Models\dong_san_pham;
use App\Models\gio_hang;
use App\Models\mo_ta;
use App\Models\nha_cung_cap;
use App\Models\tuy_chon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class san_phamController extends Controller
{
    //
    public function view_san_pham()
    {
        return view('sanpham');
    }

    public function lay_san_pham_all()
    {
        $dongSanPhams = dong_san_pham::all();     
        $data = [
            'dong_san_pham' => []
        ];
    
        foreach ($dongSanPhams as $dongSanPham) {
            
            $item = [
                'ma_dong_san_pham' => $dongSanPham->ma_dong_san_pham,
                'ten_dong_san_pham' => $dongSanPham->ten_dong_san_pham,
                'mo_ta'=>$dongSanPham->mo_ta,
                'loai_san_pham' => []
            ];
    
            foreach ($dongSanPham->loai_san_pham as $loaiSanPham) {
                
                $loaiItem = [
                    'ma_loai_san_pham' => $loaiSanPham->ma_loai_san_pham,
                    'ma_dong_san_pham'=>$loaiSanPham->ma_dong_san_pham,
                    'ten_loai_san_pham' => $loaiSanPham->ten_loai_san_pham,
                    'mo_ta' => $loaiSanPham->mo_ta,
                    'san_pham' => []
                ];
    
                foreach ($loaiSanPham->san_pham as $sanPham) { 
                   
                    $sanPhamItem = [
                        'ma_san_pham' => $sanPham->ma_san_pham,
                        'ma_loai_san_pham'=>$sanPham->ma_loai_san_pham,                
                        'ten_san_pham' => $sanPham->ten_san_pham,
                        'bien_the_san_pham'=>[],
                        'nha_cung_cap' => [
                            'ma_nha_cung_cap' => $sanPham->nha_cung_cap->ma_nha_cung_cap,
                            'ten_nha_cung_cap' => $sanPham->nha_cung_cap->ten_nha_cung_cap,
                            'dia_chi' => $sanPham->nha_cung_cap->dia_chi,
                            'so_dien_thoai' => $sanPham->nha_cung_cap->so_dien_thoai,
                            'email' => $sanPham->nha_cung_cap->email,
                        ],
                        'khuyen_mai_san_pham' => $sanPham->khuyen_mai_san_pham->map(function($khuyenMai) {
                            return [
                                'ma_khuyen_mai' => $khuyenMai->ma_khuyen_mai,
                                'muc_giam_gia' => $khuyenMai->muc_giam_gia,
                                'ngay_bat_dau' => $khuyenMai->ngay_bat_dau,
                                'ngay_ket_thuc' => $khuyenMai->ngay_ket_thuc,
                                'dieu_kien_ap_dung' => $khuyenMai->dieu_kien_ap_dung,
                            ];
                        }),
                        'anh_san_pham' => $sanPham->anh_san_pham->map(function($anh) {
                            return [
                                'ma_anh_san_pham' => $anh->ma_anh_san_pham,
                                'url_anh' => $anh->url_anh,
                                'la_anh_chinh' => $anh->la_anh_chinh,
                            ];
                        }),
                    ];

                    foreach($sanPham->bien_the_san_pham as $bthe_sp)
                    {
                        $bienthesp=[
                            'ma_bien_the'=>$bthe_sp->ma_bien_the,
                            'ma_san_pham'=>$bthe_sp->ma_san_pham,
                            'mau_sac'=>$bthe_sp->mau_sac,
                            'loai_da'=>$bthe_sp->loai_da,
                            'dung_tich'=>$bthe_sp->dung_tich,
                            'so_luong_ton_kho'=>$bthe_sp->so_luong_ton_kho,
                            'gia_ban'=>$bthe_sp->gia_ban,
                        ];

                        $sanPhamItem['bien_the_san_pham'][]=$bienthesp;

                    }
    
                    $loaiItem['san_pham'][] = $sanPhamItem; 
                }
    
                $item['loai_san_pham'][] = $loaiItem; 
            }
    
            $data['dong_san_pham'][] = $item; 
        }
    
        // Trả về dữ liệu dưới dạng JSON
        return response()->json($data);
    }
    
    public function lay_san_pham_phan_trang(Request $request)
    {
        $sosanpham = $request->input('so_san_pham', 4);
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
                    ->paginate($sosanpham);
        return response()->json($sanpham);
    }

    public function lay_san_pham()
    {
        
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham','bien_the_san_pham'])->where('trang_thai',"1")->get();
        
        return response()->json($sanpham);
    }


    public function loc_san_pham_theo_loai(Request $request)
    {

        $sanpham=san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham','bien_the_san_pham'])->where('ma_loai_san_pham',$request->ma_loai_san_pham)->get();
        return response()->json($sanpham,200);


    }

    public function loc_san_pham_theo_dong(Request $request)
    {
        $loaiSanPham = loai_san_pham::where('ma_dong_san_pham', $request->input('ma_dong_san_pham'))->get();
        $maLoaiSanPham = $loaiSanPham->pluck('ma_loai_san_pham');
        $sanpham = san_pham::whereIn('ma_loai_san_pham', $maLoaiSanPham)->get();
        return response()->json($sanpham,200);
    }

    public function chi_tiet_san_pham(Request $request)
    {
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
        ->where('ma_san_pham', $request->ma_san_pham)
        ->first();
        return response()->json($sanpham,200);
    }

    public function loc_san_pham_theo_gia(Request $request)
    {
        $min = $request->input('min', 0);
        $max = $request->input('max', 0);

        $sanpham = san_pham::when($min != 0 && $max == 0, function ($query) use ($min) {
            return $query->where('gia_ban', '>=', $min);
        })
        ->when($min == 0 && $max != 0, function ($query) use ($max) {
            return $query->where('gia_ban', '<=', $max);
        })
        ->when($min != 0 && $max != 0, function ($query) use ($min, $max) {
            return $query->whereBetween('gia_ban', [$min, $max]);
        })
        ->get();

        return response()->json($sanpham, 200);
    }

    public function tim_san_pham(Request $request)
    {

    }


    public function themSanPhamVaBienThe(Request $request)
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            $validated = $request->validate([
                'ten_san_pham' => 'required|string|max:255',
                'ma_loai_san_pham' => 'required|string|exists:loai_san_pham,ma_loai_san_pham',
                'ma_nha_cung_cap' => 'required|string|exists:nha_cung_cap,ma_nha_cung_cap',
                'bien_the.*.mau_sac' => 'nullable|string|max:100', 
                'bien_the.*.loai_da' => 'nullable|string|max:100',  
                'bien_the.*.dung_tich' => 'nullable|string|max:100',  
                // 'bien_the.*.so_luong_ton_kho' => 'nullable|integer|min:0',
                // 'bien_the.*.gia_ban' => 'nullable|numeric|min:0',
            
            ]);

            // Tạo sản phẩm mới bằng phương thức create
            $sanPham = san_pham::create([
                'ten_san_pham' => $request->ten_san_pham,
                'ma_loai_san_pham' => $request->ma_loai_san_pham,
                'ma_nha_cung_cap' => $request->ma_nha_cung_cap,
                'trang_thai'=>"1"
            ]);

            // Xử lý hình ảnh
            if ($request->hasFile('images')) {
                $bien = 0;
                foreach ($request->file('images') as $file) {
                    try {
                        // Tải từng hình ảnh lên Cloudinary
                        $result = Cloudinary::upload($file->getRealPath(), [
                            'folder' => 'PJ_MYPHAM/SanPham',
                        ]);

                        // Lưu thông tin vào bảng anh_san_pham
                        anh_san_pham::create([
                            'ma_san_pham' => $sanPham->ma_san_pham,  // Đảm bảo rằng chỉ có một ma_san_pham duy nhất
                            'url_anh' => $result->getSecurePath(),  
                            'la_anh_chinh' => $bien === 0 ? 1 : 0,  
                        ]);
                        $bien++; // Tăng chỉ số để kiểm tra ảnh chính

                    } catch (\Exception $e) {
                        // Xử lý lỗi nếu có
                        $error = "Lỗi: " . $e->getMessage();
                        return response()->json(['error' => 'Tệp không hợp lệ']);
                    }
                }
            }

            // Thêm các biến thể sản phẩm
            if ($request->has('bien_the')) {
                $bienTheData = json_decode($request->bien_the, true);
                foreach ($bienTheData as $bienThe) {
                    // Lưu các biến thể sản phẩm vào cơ sở dữ liệu
                    bien_the_san_pham::create([
                        'ma_san_pham' => $sanPham->ma_san_pham,  // Đảm bảo rằng biến thể được liên kết với sản phẩm đúng cách
                        'mau_sac' => $bienThe['mau_sac'] ?? null,
                        'loai_da' => $bienThe['loai_da'] ?? null,
                        'dung_tich' => $bienThe['dung_tich'] ?? null,  
                        'so_luong_ton_kho' => 0,
                        'gia_ban' => '0',
                        'trang_thai'=>"1"
                    ]);
                }
            }

            if($request->has('mo_ta'))
            {
                $motaData=json_decode($request->mo_ta,true);
                foreach($motaData as $mota)
                {
                    $mo_ta=mo_ta::create([
                        'ma_san_pham'=>$sanPham->ma_san_pham,
                        'ten_mo_ta'=>$mota['ten_mo_ta']??null
                    ]);
                    if($request->has('chi_tiet_mo_ta'))
                    {
                        $ct_motaData=json_decode($request->chi_tiet_mo_ta,true);
                        foreach($ct_motaData as $ct_mota)
                        {
                            if(!empty($ct_mota['ma_mo_ta']) &&$mota['ma_mo_ta']==$ct_mota['ma_mo_ta'])
                            {
                                chi_tiet_mo_ta::create([
                                    'ma_mo_ta'=>$mo_ta->ma_mo_ta,
                                    'tieu_de'=>$ct_mota['tieu_de'],
                                    'noi_dung'=>$ct_mota['noi_dung']
                                ]);
                            }
                        }
                    }
                }
            }

            // Trả về thông báo thành công
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm thành công!',
                'redirect_url' => route('showquanlysanpham'), // Gọi đúng tên route đã được gán
            ], 200);


        } catch (\Exception $e) {
            // Xử lý lỗi nếu có và trả về thông báo lỗi chi tiết
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



   

    public function xoa_san_pham(Request $request)
    {
        $sanpham=san_pham::where('ma_san_pham',$request->input('ma_san_pham'))->first();
        $sanpham->delete();
        return Response()->json(['message'=>'Xóa Thành Công!'],200);
    }

    public function cap_nhat_san_pham(Request $request)
    {
            // Xác thực yêu cầu
        $request->validate([
            'ma_san_pham' => 'required|integer|exists:san_pham,ma_san_pham',
            'ma_loai_san_pham' => 'required|integer|exists:loai_san_pham,ma_loai_san_pham',
            'ma_nha_cung_cap' => 'required|integer|exists:nha_cung_cap,ma_nha_cung_cap',
            'ten_san_pham' => 'required|string|max:255',
        ]);

        try {
          
            $sanPham = san_pham::find($request->ma_san_pham);

            
            $sanPham->update([
                'ma_loai_san_pham' => $request->ma_loai_san_pham,
                'ma_nha_cung_cap' => $request->ma_nha_cung_cap,
                'ten_san_pham' => $request->ten_san_pham,
            ]);

            
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được cập nhật thành công.',
                'data' => $sanPham
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage()
            ]);
        }
        
    }

    public function lay_san_pham_form(Request $request)
    {
        $sosanpham = $request->input('so_san_pham', 4);
        $sanpham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'anh_san_pham'])
                    ->paginate($sosanpham);
        return response()->json($sanpham);
    }

    public function edit($id)
    {
        $sanPham = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'mo_ta.chi_tiet_mo_ta','anh_san_pham'])->find($id);

        if (!$sanPham) {
            return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
        }

        $danhSachMoTa = $sanPham->mo_ta;
        $danhSachLoaiSanPham = loai_san_pham::all(['ma_loai_san_pham', 'ten_loai_san_pham']);
        $danhSachNhaCungCap = nha_cung_cap::all(['ma_nha_cung_cap', 'ten_nha_cung_cap']);
        // Log::info(['danhSachMoTa' => $danhSachMoTa]);
        Log::info('San Pham:', ['sanPham' => $sanPham]);
        return response()->json([
            'sanPham' => $sanPham,
            'danhSachLoaiSanPham' => $danhSachLoaiSanPham,
            'danhSachNhaCungCap' => $danhSachNhaCungCap,
            'danhSachMoTa' => $danhSachMoTa
        ]);
    }

    
    public function update(Request $request, $ma_san_pham)
    {
        try {
            Log::info('Dữ liệu nhận được từ request:', $request->all());
            // Cập nhật thông tin sản phẩm
            $sanPham = san_pham::findOrFail($ma_san_pham);
            $sanPham->ten_san_pham = $request->input('ten_san_pham');
            // $sanPham->ma_loai_san_pham = $request->input('ma_loai_san_pham');
            // $sanPham->ma_nha_cung_cap = $request->input('ma_nha_cung_cap');
            $sanPham->save();
    
            // Cập nhật ảnh sản phẩm (xóa ảnh cũ nếu có và thêm ảnh mới)
            // Xử lý ảnh sản phẩm
        

    
            // // Cập nhật mô tả sản phẩm
          
            if ($request->has('danhSachMoTa')) {
                // Duyệt qua từng mô tả trong danh sách
                foreach ($request->input('danhSachMoTa') as $mota) {
                    // Tìm mô tả đã tồn tại cho sản phẩm, nếu không có thì tạo mới
                    $moTa = mo_ta::updateOrCreate(
                        ['ma_mo_ta' => $mota['ma_mo_ta']], // Điều kiện tìm kiếm
                        [
                            'ten_mo_ta' => $mota['ten_mota'],  // Cập nhật tên mô tả
                            'ma_san_pham' => $ma_san_pham // Cập nhật mã sản phẩm
                        ] // Dữ liệu cần cập nhật
                    );

                    // Cập nhật chi tiết mô tả nếu có
                    if (isset($mota['danhSachChiTietMoTa'])) {
                        foreach ($mota['danhSachChiTietMoTa'] as $chiTiet) {
                            // Tìm chi tiết mô tả đã tồn tại và cập nhật, nếu không có thì tạo mới
                            chi_tiet_mo_ta::updateOrCreate(
                                ['ma_mo_ta' => $moTa->ma_mo_ta], // Điều kiện tìm kiếm
                                [
                                    'tieu_de' => $chiTiet['tieude'], 
                                    'noi_dung' => $chiTiet['noidung']
                                ]
                            );
                        }
                    }
                }
            }
    
            return response()->json(['success' => 'Cập nhật sản phẩm thành công!',  'redirect_url' => route('showquanlysanpham'),], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
            return response()->json(['error' => 'Cập nhật sản phẩm không thành công. Vui lòng thử lại.'], 500);
        }
    }
   

public function xoaSanPham($id)
{
    Log::info("Bắt đầu xóa sản phẩm với ID: {$id}");

    // Tìm sản phẩm cần xóa
    $sanPham = san_pham::find($id);

    if (!$sanPham) {
        Log::warning("Sản phẩm không tồn tại với ID: {$id}");
        return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
    }

    Log::info("Sản phẩm tìm thấy: " . $sanPham->toJson());

    // Kiểm tra sản phẩm có trong giỏ hàng
    $maBienThe = $sanPham->bien_the_san_pham->pluck('ma_bien_the');
    Log::info("Mã biến thể liên quan: " . json_encode($maBienThe));

    $sanPhamTrongGioHang = gio_hang::whereIn('ma_bien_the', $maBienThe)->exists();
    $sanPhamTrongDonDat = chi_tiet_don_dat::whereIn('ma_bien_the', $maBienThe)->exists();

    Log::info("Kiểm tra giỏ hàng: " . ($sanPhamTrongGioHang ? "Có" : "Không"));
    Log::info("Kiểm tra chi tiết đơn đặt: " . ($sanPhamTrongDonDat ? "Có" : "Không"));

    if ($sanPhamTrongGioHang || $sanPhamTrongDonDat) {
        Log::warning("Sản phẩm liên quan dữ liệu, chuyển sang trạng thái ẩn.");
        $sanPham->trang_thai = 0; // Giả sử 0 là trạng thái ẩn
        $sanPham->save();

        Log::info("Sản phẩm đã được ẩn thành công.");
        return response()->json(['message' => 'Sản phẩm đã được ẩn vì có liên quan dữ liệu'], 200);
    }

    // Xóa các liên kết liên quan
    Log::info("Bắt đầu xóa liên kết liên quan.");

    // Xóa ảnh sản phẩm
    $sanPham->anh_san_pham()->delete();
    Log::info("Đã xóa ảnh sản phẩm.");

    // Xóa biến thể sản phẩm
    $sanPham->bien_the_san_pham()->delete();
    Log::info("Đã xóa biến thể sản phẩm.");

    // Xóa mô tả và chi tiết mô tả
    $sanPham->mo_ta->each(function ($moTa) {
        Log::info("Xóa chi tiết mô tả của mô tả ID: " . $moTa->id);
        $moTa->chi_tiet_mo_ta()->delete();

        Log::info("Xóa mô tả ID: " . $moTa->id);
        $moTa->delete();
    });

    // Cuối cùng, xóa sản phẩm
    $sanPham->delete();
    Log::info("Sản phẩm và dữ liệu liên quan đã được xóa thành công.");

    return response()->json([
        'success' => true,
        'message' => 'Sản phẩm và các dữ liệu liên quan đã được xóa thành công'
    ], 200);
}



}
