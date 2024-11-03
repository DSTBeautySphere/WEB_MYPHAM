<?php

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
    Route::post('/themsanpham',[san_phamController::class,'them_san_pham']);
    Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
    Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
    

    //loai_san_pham
    Route::post('/themloaisanpham',[loai_san_phamController::class,'themLoaiSanPham']);
    Route::post('/xoaloaisanpham',[loai_san_phamController::class,'xoaLoaiSanPham']);
    Route::post('/sualoaisanpham',[loai_san_phamController::class,'suaLoaiSanPham']);

    //nha cung cap
    Route::post('/themnhacungcap', [nha_cung_capController::class, 'themNCC']);
    Route::delete('/xoanhacungcap/{id}',[nha_cung_capController::class,'xoaNCC']);
    Route::post('/suanhacungcap/{id}',[nha_cung_capController::class,'suaNCC']);





// });