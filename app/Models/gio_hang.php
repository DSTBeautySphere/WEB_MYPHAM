<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gio_hang extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_gio_hang';
    protected $table = 'gio_hang';
    
    protected $fillable = [
        'ma_user',
        'ngay_tao',
        'trang_thai',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user', 'ma_user');
    }

    public function chi_tiet_gio_hang()
    {
        return $this->hasMany(chi_tiet_gio_hang::class, 'ma_gio_hang', 'ma_gio_hang');
    }
}
