<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class don_dat extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_don_dat';
    protected $table = 'don_dat';
    
    protected $fillable = [
        'ma_user',
        'ngay_dat',
        'tong_tien',
        'trang_thai',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user', 'ma_user');
    }

    public function hoa_don()
    {
        return $this->hasOne(hoa_don::class, 'ma_don_dat', 'ma_don_dat');
    }

    public function doi_tra()
    {
        return $this->hasMany(doi_tra::class, 'ma_don_dat', 'ma_don_dat');
    }
}
