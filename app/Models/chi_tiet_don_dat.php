<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chi_tiet_don_dat extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_don_dat';
    protected $primaryKey = 'ma_chi_tiet_don_dat';
    protected $fillable = [
        'ma_don_dat',
        'ma_bien_the',
        'so_luong',
        'gia_ban',
        'ten_san_pham',
        'chi_tiet_tuy_chon',
    ];

    public $timestamps = false;


    public function don_dat()
    {
        return $this->belongsTo(don_dat::class, 'ma_don_dat');
    }

    public function bien_the_san_pham()
    {
        return $this->belongsTo(bien_the_san_pham::class, 'ma_bien_the');
    }
    // public function getThanhTienAttribute()
    // {
    //     return $this->so_luong * $this->gia_ban;
    // }
}
