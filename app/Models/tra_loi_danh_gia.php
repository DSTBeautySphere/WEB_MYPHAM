<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tra_loi_danh_gia extends Model
{
    use HasFactory;

    protected $table = 'tra_loi_danh_gia';
    protected $primaryKey = 'ma_tra_loi';
    protected $fillable = ['ma_danh_gia', 'nguoi_tra_loi', 'noi_dung', 'ngay_tra_loi'];

    public function danh_gia()
    {
        return $this->belongsTo(danh_gia::class, 'ma_danh_gia');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'ma_user');
    // }
}
