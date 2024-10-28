<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';
    protected $primaryKey = 'ma_voucher';
    protected $fillable = [
        'ten_voucher', 
        'muc_giam_gia', 
        'loai_giam_gia', 
        'gia_tri_dieu_kien', 
        'giam_gia_toi_da', 
        'so_luong', 
        'ngay_bat_dau', 
        'ngay_ket_thuc', 
        'dieu_kien_ap_dung', 
        'trang_thai'
    ];
}
