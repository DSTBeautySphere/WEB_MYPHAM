<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\Client\VnPayController;
use App\Http\Controllers\Client\VoucherController;
use App\Http\Controllers\danh_giaController;
use App\Http\Controllers\don_datController;
use App\Http\Controllers\gio_hangController;
use App\Http\Controllers\loai_san_phamController;
use App\Http\Controllers\nha_cung_capController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
include 'admin.php';
include 'wf.php';

Route::get('/test',[App\Http\Controllers\TestController::class,'tat_ca_san_pham']);

// Route để hiển thị form tải lên ảnh
Route::get('/show', [App\Http\Controllers\TestController::class, 'showForm']);

// Route để xử lý tải lên ảnh
Route::post('/upload-image', [App\Http\Controllers\TestController::class, 'uploadImage']);

Route::middleware(['user'])->group(function(){

});
Route::post('/dangKy', [AuthController::class, 'dangKy']);
Route::post('/dangNhap', [AuthController::class, 'dangNhap']);
Route::post('/dangXuat', [AuthController::class, 'dangXuat']);


Route::post('/themgioHang', [gio_hangController::class,"themSP_GH"]);
Route::get('/chitietgiohang', [gio_hangController::class,"chiTietGioHang"]);
Route::post('/capnhatsoluong', [gio_hangController::class,"updateQuantity"]);
Route::post('/xoagiohang', [gio_hangController::class,"deleteCart"]);

Route::post('/dathang', [don_datController::class,"themDonDat"]);

//san_pham
//Route::get('/view_sanpham',[App\Http\Controllers\san_phamController::class,'lay_san_pham']);
Route::get('/sanpham',[App\Http\Controllers\san_phamController::class,'lay_san_pham']);

Route::get('/view_sanpham',[App\Http\Controllers\san_phamController::class,'view_san_pham']);
Route::get('/laysanphamall',[App\Http\Controllers\san_phamController::class,'lay_san_pham_all']);

Route::get('/sanphamphantrang',[App\Http\Controllers\san_phamController::class,'lay_san_pham_phan_trang']);
Route::get('/locsanphamtheoloai',[App\Http\Controllers\san_phamController::class,'loc_san_pham_theo_loai']);
//Route::get('/locsanphamtheodong',[App\Http\Controllers\san_phamController::class,'loc_san_pham_theo_dong']);
Route::get('/chitietsanpham',[App\Http\Controllers\san_phamController::class,'chi_tiet_san_pham']);
Route::get('/locsanphamtheogia',[App\Http\Controllers\san_phamController::class,'loc_san_pham_theo_gia']);
Route::post('/danhgiasanpham', [danh_giaController::class,'guiDanhGia']);

//nha cung cap
Route::get('/nhacungcap',[nha_cung_capController::class, 'danh_sach_NCC' ]);



Route::get('/laysanpham',[App\Http\Controllers\san_phamController::class,'lay_san_pham']);
//end_san_pham


//dong_san_pham
Route::get('/view_dongsanpham',[App\Http\Controllers\dong_san_phamController::class,'view_dongsanpham']);
Route::get('/laydongsanpham',[App\Http\Controllers\dong_san_phamController::class,'lay_dong_san_pham']);


// loai san pham

Route::get('/danhsachloai',[loai_san_phamController::class,'loadLoaiSanPham']);
Route::get('/laynhomtuychontheoloai',[App\Http\Controllers\loai_san_phamController::class,'layNhomTuyChonTheoLoai']);
Route::get('/layloaisanpham',[App\Http\Controllers\loai_san_phamController::class,'layLoaiSanPham']);
Route::get('/laynhomtuychon',[App\Http\Controllers\tuy_chonController::class,'layNhomTuyChon']);


Route::controller(ProductController::class)->group(function () {
  Route::get('/products/category/{id}', 'index');
  Route::get('/product/{id}', 'show');
  Route::get('/products', 'getAll');
  Route::get('/categories', 'getAllCategories');
 
});

Route::controller(ClientAuthController::class)->group(function () {
  Route::post('/register', 'register');
  Route::post('/login', 'login');
  Route::post('/username/check', 'checkUsername');
  Route::post('/password/change', 'changePassword');
  Route::post('/sendemailupdatepassword', 'sendEmailUpdatePassword');
});

Route::controller(CartController::class)->group(function () {
  Route::post('/cart/addToCart', 'store');
  Route::post('/cart', 'index');
  Route::get('/cart/{id}/delete', 'destroy');
});

Route::controller(VnPayController::class)->group(function () {
  Route::post('/create-payment', 'createPayment');
});

Route::controller(OrderController::class)->group(function () {
  Route::post('/order/{id}/status', 'edit');
  Route::get('/order/user/{id}', 'show');
  Route::get('/order/{id}', 'detail');
});

Route::controller(ReviewController::class)->group(function () {
  Route::get('/review/{id}', 'index');
  Route::post('/review/create', 'store');
  Route::post('/checkboughtproduct', 'checkUserPurchasedProduct');
});

Route::controller(VoucherController::class)->group(function () {
  Route::get('/getvoucher', 'getVouchers');
  Route::post('/storevoucher', 'storeVoucher');
  
});