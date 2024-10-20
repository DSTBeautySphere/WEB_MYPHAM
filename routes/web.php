<?php

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

Route::get('/test',[App\Http\Controllers\TestController::class,'tat_ca_san_pham']);

//san_pham
Route::get('/view_sanpham',[App\Http\Controllers\san_phamController::class,'view_san_pham']);
Route::get('/sanpham',[App\Http\Controllers\san_phamController::class,'lay_san_pham']);
Route::get('/sanphamphantrang',[App\Http\Controllers\san_phamController::class,'lay_san_pham_phan_trang']);
Route::get('/locsanphamtheoloai',[App\Http\Controllers\san_phamController::class,'loc_san_pham_theo_loai']);
Route::get('/locsanphamtheodong',[App\Http\Controllers\san_phamController::class,'loc_san_pham_theo_dong']);
Route::get('/chitietsanpham',[App\Http\Controllers\san_phamController::class,'chi_tiet_san_pham']);
Route::get('/locsanphamtheogia',[App\Http\Controllers\san_phamController::class,'loc_san_pham_theo_gia']);
//CURD
Route::post('/themsanpham',[App\Http\Controllers\san_phamController::class,'them_san_pham']);
Route::post('/xoasanpham',[App\Http\Controllers\san_phamController::class,'xoa_san_pham']);
Route::post('/capnhatsanpham',[App\Http\Controllers\san_phamController::class,'cap_nhat_san_pham']);
//