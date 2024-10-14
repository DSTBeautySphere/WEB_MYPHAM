<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chi_tiet_gio_hang extends Model
{
    use HasFactory;
    protected $table = 'chi_tiet_gio_hang';

    protected $primaryKey = 'ma_gio_hang';


    protected $fillable = [
        'ma_gio_hang',
        'ma_san_pham',
        'so_luong',
        'gia_ban',
        'created_at',
        'updated_at'
    ];

    public function gio_hang()
    {
        return $this->belongsTo(gio_hang::class, 'ma_gio_hang', 'ma_gio_hang');
    }

    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham', 'ma_san_pham');
    }
}
