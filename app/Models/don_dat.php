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
        'tong_tien', 
        'trang_thai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user');
    }

    public function chi_tiet_don_dat()
    {
        return $this->hasMany(chi_tiet_don_dat::class, 'ma_don_dat');
    }
}
