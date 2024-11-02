<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dong_san_pham extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_dong_san_pham';
    protected $table = 'dong_san_pham';
    
    protected $fillable = [
        'ten_dong_san_pham',
        'mo_ta',
    ];
    public $timestamps = false;


    public function loai_san_pham()
    {
        return $this->hasMany(loai_san_pham::class, 'ma_dong_san_pham', 'ma_dong_san_pham');
    }
}
