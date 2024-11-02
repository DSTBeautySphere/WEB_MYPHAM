<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class danh_gia extends Model
{
    use HasFactory;

    protected $table = 'danh_gia';
    protected $primaryKey = 'ma_danh_gia';
    protected $fillable = ['ma_san_pham', 'ma_user', 'noi_dung', 'diem', 'ngay_danh_gia'];
    public $timestamps = false;


    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user');
    }
    public function tra_loi_danh_gia()
    {
        return $this->hasMany(tra_loi_danh_gia::class, 'ma_danh_gia');
    }
}
