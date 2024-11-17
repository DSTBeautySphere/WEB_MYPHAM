<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'admin';
    protected $primaryKey = 'ma_admin';

    protected $fillable = [
        'email', 'ho_ten', 'so_dien_thoai', 'anh_dai_dien', 'trang_thai','mat_khau'
    ];

    protected $hidden = [
        'mat_khau'
    ];

    public $timestamps=false;
    protected $casts = [
        'mat_khau' => 'hashed',
    ];

    // public function getAuthPassword()
    // {
    //     return $this->mat_khau;
    // }
}
