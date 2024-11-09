<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\danh_giaController;
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
include 'web_admin.php';

Route::get('/test',[App\Http\Controllers\TestController::class,'tat_ca_san_pham']);


Route::middleware(['user'])->group(function(){

});
Route::post('/dangKy', [AuthController::class, 'dangKy']);
Route::post('/dangNhap', [AuthController::class, 'dangNhap']);
Route::post('/dangXuat', [AuthController::class, 'dangXuat']);


Route::post('/themgioHang', [gio_hangController::class,"themSP_GH"]);


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


//CURD
Route::post('/themsanpham',[App\Http\Controllers\san_phamController::class,'them_san_pham']);
Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
//F
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
