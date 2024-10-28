<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hoa_don extends Model
{
    use HasFactory;

    protected $table = 'hoa_don';
    protected $primaryKey = 'ma_hoa_don';
    protected $fillable = [
        'ma_user', 
        'ma_don_dat', 
        'ngay_thanh_toan', 
        'phuong_thuc_thanh_toan', 
        'tong_tien'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user');
    }

    public function don_dat()
    {
        return $this->belongsTo(don_dat::class, 'ma_don_dat');
    }
}
