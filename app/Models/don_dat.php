<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class don_dat extends Model
{
    use HasFactory;

    protected $table = 'don_dat';
    protected $primaryKey = 'ma_don_dat';
    protected $fillable = [
        'ma_user',
        'ngay_dat',
        'ma_voucher',
        'giam_gia',
        'tong_tien_ban_dau',
        'phi_van_chuyen',
        'tong_tien_cuoi_cung',
        'so_dien_thoai',
        'dia_chi_giao_hang',
        'ngay_du_kien_giao',
        'trang_thai_giao_hang',
        'ghi_chu',
        'phuong_thuc_thanh_toan',
        'ngay_thanh_toan',
        'trang_thai_thanh_toan',
        'trang_thai_don_dat',
    ];

    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user');
    }

    public function chi_tiet_don_dat()
    {
        return $this->hasMany(chi_tiet_don_dat::class, 'ma_don_dat');
    }
  
  
}
