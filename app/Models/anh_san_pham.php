<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anh_san_pham extends Model
{
    use HasFactory;

    protected $table = 'anh_san_pham';
    protected $primaryKey = 'ma_anh_san_pham';
    protected $fillable = ['ma_san_pham', 'url_anh', 'la_anh_chinh','anh1','anh2','anh3'];
    public $timestamps = false;


    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham');
    }
}
