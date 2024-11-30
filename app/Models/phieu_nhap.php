<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class phieu_nhap extends Model
{
    use HasFactory;
    
    protected $table = 'phieu_nhap';

  
    protected $primaryKey = 'ma_phieu_nhap';
    public $timestamps = false;

    
    protected $fillable = [
        'code_phieu_nhap',
        'ma_nha_cung_cap',
        'ngay_nhap',
        'tong_so_luong',
        'tong_gia_tri',
        'ghi_chu',
        'trang_thai',
    ];

    
    public function chi_tiet_phieu_nhap()
    {
        return $this->hasMany(chi_tiet_phieu_nhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }
    public function nha_cung_cap()
    {
        return $this->belongsTo(nha_cung_cap::class, 'ma_nha_cung_cap', 'ma_nha_cung_cap');
    }
}
