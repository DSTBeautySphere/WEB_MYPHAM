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
        'ngay_nhap',
        'tong_so_luong',
        'tong_gia_tri',
        'ghi_chu',
    ];

    
    public function chi_tiet_phieu_nhap()
    {
        return $this->hasMany(chi_tiet_phieu_nhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }
}
