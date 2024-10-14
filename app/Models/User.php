<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'ma_user';
    protected $table = 'user';
    
    protected $fillable = [
        'ten_dang_nhap',
        'mat_khau',
        'ho_ten',
        'gioi_tinh',
        'so_dien_thoai',
        'dia_chi',
        'so_chung_minh_thu',
        'email',
        'phan_quyen',
        'xac_thuc',
        'kich_hoat',
        'trang_thai',
        'created_at',
        'updated_at'
    ];
     protected $hidden = [
        'mat_khau',
    ];

    public function gio_hang()
    {
        return $this->hasMany(gio_hang::class, 'ma_user', 'ma_user');
    }

    public function don_dat()
    {
        return $this->hasMany(don_dat::class, 'ma_user', 'ma_user');
    }

    public function hoa_don()
    {
        return $this->hasMany(hoa_don::class, 'ma_user', 'ma_user');
    }

    public function vi_dien_tu()
    {
        return $this->hasOne(vi_dien_tu::class, 'ma_user', 'ma_user');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    // /**
    //  * The attributes that should be hidden for serialization.
    //  *
    //  * @var array<int, string>
    //  */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     'password' => 'hashed',
    // ];
}
