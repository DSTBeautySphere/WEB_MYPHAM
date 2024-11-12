<?php

use App\Http\Controllers\bien_the_san_phamController;
use App\Http\Controllers\dong_san_phamController;
use App\Http\Controllers\loai_san_phamController;
use App\Http\Controllers\nha_cung_capController;
use App\Http\Controllers\san_phamController;
use App\Models\loai_san_pham;
use Illuminate\Support\Facades\Route;

//san pham
// Route::post('/themsanpham',[san_phamController::class,'them_san_pham']);
// Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
// Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
// Route::post('/themnhacungcap',[nha_cung_capController::class,'themNCC']);

// Route::prefix('admin')->group(function () {
    //san pham
    
    Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
    Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
    Route::get('/showthemsanpham',[App\Http\Controllers\san_phamController::class,'showThemSanPham']);
    Route::post('/themsanpham',[App\Http\Controllers\san_phamController::class,'themSanPhamVaBienThe']);


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

    //don hang
    Route::post('themdondat',[App\Http\Controllers\don_datController::class,'themDonDat']);

    //chi tiet don dat
    Route::post('themchitietdondat',[App\Http\Controllers\chi_tiet_don_datController::class,'themChiTietDonDat']);


    // //admincontroller
    // Route::get('/gdthemsanpham',[App\Http\Controllers\AdminController::class,'showThemSanPham']);
    // Route::post('/gdthemsanpham',[App\Http\Controllers\AdminController::class,'themSanPhamVaBienThe']);


// });