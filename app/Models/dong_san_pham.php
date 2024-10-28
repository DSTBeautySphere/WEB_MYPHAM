<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dong_san_pham extends Model
{
    use HasFactory;

    protected $table = 'dong_san_pham';
    protected $primaryKey = 'ma_dong_san_pham';
    protected $fillable = ['ten_dong_san_pham', 'mo_ta'];
}
