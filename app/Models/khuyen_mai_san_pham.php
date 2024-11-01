<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class khuyen_mai_san_pham extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_khuyen_mai';
    protected $table = 'khuyen_mai_san_pham';
    
    protected $fillable = [
        'ma_san_pham',
        'muc_giam_gia',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'dieu_kien_ap_dung',
    ];

    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham', 'ma_san_pham');
    }
}
