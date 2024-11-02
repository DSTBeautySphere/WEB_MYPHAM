<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class san_pham extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_san_pham';
    protected $table = 'san_pham';
    
    protected $fillable = [
        'ma_loai_san_pham',
        'ma_nha_cung_cap',
        'ten_san_pham'
    ];
    public $timestamps = false;


    public function bien_the_san_pham()
    {
        return $this->hasMany(bien_the_san_pham::class,'ma_san_pham', 'ma_san_pham');
    }

    public function loai_san_pham()
    {
        return $this->belongsTo(loai_san_pham::class, 'ma_loai_san_pham', 'ma_loai_san_pham');
    }

    public function nha_cung_cap()
    {
        return $this->belongsTo(nha_cung_cap::class, 'ma_nha_cung_cap', 'ma_nha_cung_cap');
    }

    public function khuyen_mai_san_pham()
    {
        return $this->hasMany(khuyen_mai_san_pham::class, 'ma_san_pham', 'ma_san_pham');
    }
  
    public function anh_san_pham()
    {
        return $this->hasMany(anh_san_pham::class, 'ma_san_pham', 'ma_san_pham');
    }
}
