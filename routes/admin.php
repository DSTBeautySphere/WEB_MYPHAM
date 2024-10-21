<?php

use App\Http\Controllers\nha_cung_capController;
use App\Http\Controllers\san_phamController;
use Illuminate\Support\Facades\Route;

//san pham
// Route::post('/themsanpham',[san_phamController::class,'them_san_pham']);
// Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
// Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
// Route::post('/themnhacungcap',[nha_cung_capController::class,'themNCC']);

Route::prefix('admin')->group(function () {
    //san pham
    Route::post('/themsanpham',[san_phamController::class,'them_san_pham']);
    Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
    Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
    
    //nha cung cap
    Route::post('/themnhacungcap', [nha_cung_capController::class, 'themNCC']);
    Route::delete('/xoanhacungcap/{id}',[nha_cung_capController::class,'xoaNCC']);
    Route::post('/suanhacungcap/{id}',[nha_cung_capController::class,'suaNCC']);

});