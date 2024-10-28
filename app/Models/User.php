<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'ma_user';
    protected $fillable = [
        'ten_dang_nhap',
        'mat_khau',
        'ho_ten', 
        'gioi_tinh', 
        'so_dien_thoai', 
        'dia_chi', 
        'email', 
        'ngay_sinh', 
        'anh_dai_dien', 
        'xac_thuc', 
        'kich_hoat', 
        'trang_thai'
        ];
}
