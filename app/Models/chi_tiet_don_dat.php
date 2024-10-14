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
        'ma_san_pham',
        'so_luong',
        'gia_ban',
        'created_at',
        'updated_at'
    ];

    public function don_dat()
    {
        return $this->belongsTo(don_dat::class, 'ma_don_dat', 'ma_don_dat');
    }

    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham', 'ma_san_pham');
    }
}
