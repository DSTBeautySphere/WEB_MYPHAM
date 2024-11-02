<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chi_tiet_mo_ta extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_mo_ta';
    protected $primaryKey = 'ma_chi_tiet_mo_ta';
    protected $fillable = ['ma_mo_ta', 'tieu_de', 'noi_dung'];
    public $timestamps = false;

    public function mo_ta()
    {
        return $this->belongsTo(mo_ta::class, 'ma_mo_ta');
    }
}
