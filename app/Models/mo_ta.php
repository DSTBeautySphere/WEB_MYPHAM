<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mo_ta extends Model
{
    use HasFactory;
    protected $table = 'mo_ta';
    protected $primaryKey = 'ma_mo_ta';
    protected $fillable = ['ma_san_pham', 'ten_mo_ta'];
    public $timestamps = false;


    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham');
    }

    public function chi_tiet_mo_ta()
    {
        return $this->hasMany(chi_tiet_mo_ta::class, 'ma_mo_ta');
    }
}
