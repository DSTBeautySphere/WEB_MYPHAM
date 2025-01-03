<?php

use App\Http\Controllers\bien_the_san_phamController;
use App\Http\Controllers\dong_san_phamController;
use App\Http\Controllers\loai_san_phamController;
use App\Http\Controllers\nha_cung_capController;
use App\Http\Controllers\san_phamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\don_datController;
use App\Http\Controllers\khuyen_mai_san_phamController;
use App\Http\Controllers\tuy_chonController;
use App\Http\Controllers\UserController;
use App\Models\loai_san_pham;
use Illuminate\Support\Facades\Route;

//san pham
// Route::post('/themsanpham',[san_phamController::class,'them_san_pham']);
// Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
// Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
// Route::post('/themnhacungcap',[nha_cung_capController::class,'themNCC']);

// Route::prefix('admin')->group(function () {

Route::get('/admin/register', [AdminController::class, 'register']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'showLoginForm']);
    // Route::post('/login', [AdminController::class, 'login']);

    Route::middleware(['admin'])->group(function () {
       
    });
});
    //san pham
    
    Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
    Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
    Route::get('/showthemsanpham',[App\Http\Controllers\AdminController::class,'showThemSanPham']);
    Route::post('/themsanpham',[App\Http\Controllers\san_phamController::class,'themSanPhamVaBienThe']);
    Route::get('/showquanlysanpham',[App\Http\Controllers\AdminController::class,'showQuanLySanPham'])->name('showquanlysanpham');;



    //bien the san pham
    Route::get('/danhsachbienthesanpham',[bien_the_san_phamController::class,'loadBienTheSanPham']);
    Route::post('/thembienthesanpham',[bien_the_san_phamController::class,'themBienTheSanPham']);
    Route::post('/xoabienthesanpham',[bien_the_san_phamController::class,'xoaBienTheSanPham']);
    Route::post('/suabienthesanpham',[bien_the_san_phamController::class,'suaBienTheSanPham']);

    //dong san pham
    Route::get("/danhsachdongsanpham",[dong_san_phamController::class,'lay_dong_san_pham']);
    Route::post("/themdongsanpham",[dong_san_phamController::class,'them_dong_san_pham']);
    Route::post("/xoadongsanpham",[dong_san_phamController::class,'xoa_dong_san_pham']);
    Route::post("/suadongsanpham",[dong_san_phamController::class,'sua_dong_san_pham']);

    //loai_san_pham
    Route::get('/danhsachloai',[loai_san_phamController::class,'loadLoaiSanPham']);
    Route::post('/themloaisanpham',[loai_san_phamController::class,'themLoaiSanPham']);
    Route::post('/xoaloaisanpham',[loai_san_phamController::class,'xoaLoaiSanPham']);
    Route::post('/sualoaisanpham',[loai_san_phamController::class,'suaLoaiSanPham']);

    //nha cung cap
    Route::post('/themnhacungcap', [nha_cung_capController::class, 'themNCC']);
    Route::delete('/xoanhacungcap/{id}',[nha_cung_capController::class,'xoaNCC']);
    Route::post('/suanhacungcap',[nha_cung_capController::class,'suaNCC']);
    Route::get("/danhsachnhacungcap",[nha_cung_capController::class,'danh_sach_NCC']);
    //winform
    Route::get("/laynhacungcap",[nha_cung_capController::class,'layNhaCungCap']);

    //don hang
    Route::post('themdondat',[App\Http\Controllers\don_datController::class,'themDonDat']);
    Route::get('showquanlydonhang',[App\Http\Controllers\AdminController::class,'showQuanLyDonHang']);
    Route::get('/don-dat/{id}', [don_datController::class, 'show'])->name('don-dat.show');
    Route::get('/locdulieuDH', [don_datController::class, 'loc_du_lieuDH']);


    //chi tiet don dat
    Route::post('themchitietdondat',[App\Http\Controllers\chi_tiet_don_datController::class,'themChiTietDonDat']);

    //tuy chon
    Route::post("/themtuychon",[tuy_chonController::class,'themTuyChon']);
    Route::post('/laytuychon', [tuy_chonController::class, 'getTuyChonByNhom']);
    Route::post("/suatuychon",[tuy_chonController::class,'suaTuyChon']);


    // bao cao thong ke

    Route::get('/thongke', [AdminController::class, 'Statistics']);
    Route::post('/thongke/revenue', [App\Http\Controllers\AdminController::class, 'getRevenueData'])->name('thongke.revenue');


    //Khuyen mai 
    Route::get('/showkhuyenmai', [App\Http\Controllers\AdminController::class, 'showPromotion']);
    // Lấy danh sách khuyến mãi
    Route::get('/getsanpham', [AdminController::class, 'getSanPham']);
   
    Route::get('/by-loai', [AdminController::class, 'getLoai']);
    Route::get('/by-dong', [AdminController::class, 'getDong']);

    Route::get('/getsanpham-by-dong', [AdminController::class, 'getSanPhamByDong']);
    Route::get('/getsanpham-by-loai', [AdminController::class, 'getSanPhamByLoai']);

    // Lấy danh sách sản phẩm có khuyến mãi
    Route::get('/with-discount', [AdminController::class, 'getSanPhamWithDiscount']);

    Route::post('/add-discount', [AdminController::class, 'addDiscountToSanPham']);
    Route::post('/delete-discount', [AdminController::class, 'deleteDiscountFromSanPham']);

    //User
    Route::get('/showUser',[UserController::class,'showUser']);
    Route::get('/locUser',[UserController::class,'LocUser']);
    Route::get('/timKiem',[UserController::class,'timKiem']);
    Route::get('/thongTinUser/{id}',[UserController::class,'thongTinUser']);
    Route::post('/update-status/{id}', [UserController::class, 'updateStatus']);
   
// });