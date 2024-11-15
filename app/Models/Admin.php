<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'admin';
    protected $primaryKey = 'ma_admin';

    protected $fillable = [
        'email', 'ho_ten', 'so_dien_thoai', 'anh_dai_dien', 'trang_thai'
    ];

    protected $hidden = [
        'mat_khau'
    ];

    // protected $casts = [
    //     'mat_khau' => 'hashed',
    // ];

    // public function getAuthPassword()
    // {
    //     return $this->mat_khau;
    // }
}
