<?php
use Illuminate\Support\Facades\Route;
Route::get('/layanhtheosanpham',[App\Http\Controllers\WFController::class,'layAnhTheoSanPham']);
Route::post('/suaanhsanpham',[App\Http\Controllers\WFController::class,'suaAnhSanPham']);
Route::get('/laybienthetheosanpham',[App\Http\Controllers\WFController::class,'layBienTheTheoSanPham']);
Route::post('/themanhsanpham',[App\Http\Controllers\WFController::class,'themAnhSanPham']);
Route::post('/xoaanhsanpham',[App\Http\Controllers\WFController::class,'xoaAnhSanPham']);

Route::post('/themdondat',[App\Http\Controllers\WFController::class,'themDonDat']);
Route::get('/laydondat',[App\Http\Controllers\WFController::class,'layDonDat']);