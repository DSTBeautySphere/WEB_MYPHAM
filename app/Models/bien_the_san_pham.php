<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bien_the_san_pham extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_bien_the';
    protected $table = 'bien_the_san_pham';
    
    protected $fillable = [
        'ma_san_pham',
        'mau_sac',
        'loai_da',
        'dung_tich',
        'so_luong_ton_kho',
        'gia_ban',
    ];

    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham');
    }
}

