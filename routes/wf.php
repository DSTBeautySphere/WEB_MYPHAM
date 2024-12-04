<?php
use Illuminate\Support\Facades\Route;
Route::get('/layanhtheosanpham',[App\Http\Controllers\WFController::class,'layAnhTheoSanPham']);
Route::post('/suaanhsanpham',[App\Http\Controllers\WFController::class,'suaAnhSanPham']);
Route::get('/laybienthetheosanpham',[App\Http\Controllers\WFController::class,'layBienTheTheoSanPham']);
Route::post('/themanhsanpham',[App\Http\Controllers\WFController::class,'themAnhSanPham']);
Route::post('/xoaanhsanpham',[App\Http\Controllers\WFController::class,'xoaAnhSanPham']);
Route::post('/capnhatsanpham',[App\Http\Controllers\WFController::class,'capNhatSanPham']);
Route::post('/themsanphamwf',[App\Http\Controllers\WFController::class,'themSanPham']);
Route::post('/xoasanphamwf',[App\Http\Controllers\WFController::class,'xoaSanPham']);
Route::post('/suabienthewf',[App\Http\Controllers\WFController::class,'suaBienThe']);
Route::post('/thembienthewf',[App\Http\Controllers\WFController::class,'themBienThe']);
Route::post('/xoabienthewf',[App\Http\Controllers\WFController::class,'xoaBienThe']);

Route::post('/themdondat',[App\Http\Controllers\WFController::class,'themDonDat']);
Route::get('/laydondat',[App\Http\Controllers\WFController::class,'layDonDat']);
Route::get('/laychitietdon',[App\Http\Controllers\WFController::class,'layChiTietDon']);
Route::post('/capnhattrangthaidon',[App\Http\Controllers\WFController::class,'capNhatTrangThaiDon']);

Route::get('/layvoucherwf',[App\Http\Controllers\WFController::class,'layVoucher']);
Route::post('/sudungvoucherwf',[App\Http\Controllers\WFController::class,'suDungVoucher']);

Route::post('/thongkewf',[App\Http\Controllers\WFController::class,'thongKe']);
Route::get('/topsanphambanchay',[App\Http\Controllers\WFController::class,'topSanPhamBanChay']);