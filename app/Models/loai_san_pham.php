<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loai_san_pham extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_loai_san_pham';
    protected $table = 'loai_san_pham';
    
    protected $fillable = [
        'ma_dong_san_pham',
        'ten_loai_san_pham',
        'mo_ta',
        'created_at',
        'updated_at'
    ];

    public function dong_san_pham()
    {
        return $this->belongsTo(dong_san_pham::class, 'ma_dong_san_pham', 'ma_dong_san_pham');
    }

    public function san_pham()
    {
        return $this->hasMany(san_pham::class, 'ma_loai_san_pham', 'ma_loai_san_pham');
    }
}
