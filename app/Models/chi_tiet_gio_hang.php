<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chi_tiet_gio_hang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_gio_hang';
    protected $fillable = ['ma_gio_hang', 'ma_bien_the', 'so_luong', 'gia_ban'];
    public $timestamps = false;


    public function gio_hang()
    {
        return $this->belongsTo(gio_hang::class, 'ma_gio_hang');
    }

    public function bien_the_san_pham()
    {
        return $this->belongsTo(bien_the_san_pham::class, 'ma_bien_the');
    }
}
