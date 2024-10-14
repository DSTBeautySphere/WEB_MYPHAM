<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_images extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_hinh_anh';
    protected $table = 'product_images';
    
    protected $fillable = [
        'ma_san_pham',
        'duong_dan_hinh_anh',
        'created_at',
        'updated_at'
    ];

    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham', 'ma_san_pham');
    }
}
