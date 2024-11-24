<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chi_tiet_phieu_nhap extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_phieu_nhap';
    protected $primaryKey = 'ma_chi_tiet_phieu_nhap';
    public $timestamps = false;

   
    protected $fillable = [
        'ma_phieu_nhap',
        'ma_bien_the',
        'so_luong',
        'gia_nhap',
    ];

   
    public function phieu_nhap()
    {
        return $this->belongsTo(phieu_nhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }

    
    public function bien_the_san_pham()
    {
        return $this->belongsTo(bien_the_san_pham::class, 'ma_bien_the', 'ma_bien_the');
    }
}
