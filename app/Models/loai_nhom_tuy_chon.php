<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loai_nhom_tuy_chon extends Model
{
    use HasFactory;

    protected $table = 'loai_nhom_tuy_chon';
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['ma_nhom_tuy_chon', 'ma_loai_san_pham'];
    public $timestamps = false;


    public function loai_san_pham()
    {
        return $this->belongsTo(loai_san_pham::class, 'ma_loai_san_pham');
    }
    
    public function nhom_tuy_chon()
    {
        return $this->belongsTo(nhom_tuy_chon::class, 'ma_nhom_tuy_chon');
    }
}
