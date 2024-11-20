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
        'so_dien_thoai',
        'dia_chi_giao_hang',
        'ngay_du_kien_giao',
        'trang_thai_giao_hang',
        'ghi_chu'
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
    public function hoa_don()
    {
        return $this->hasOne(hoa_don::class, 'ma_don_dat');
    }
}
