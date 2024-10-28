<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gio_hang extends Model
{
    use HasFactory;

    protected $table = 'gio_hang';
    protected $primaryKey = 'ma_gio_hang';
    protected $fillable = ['ma_user', 'ma_san_pham', 'so_luong', 'gia_ban', 'ngay_tao', 'trang_thai'];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user');
    }

    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham');
    }
}
