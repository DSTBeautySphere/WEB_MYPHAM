<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tuy_chon extends Model
{
    use HasFactory;
    protected $table = 'tuy_chon';
    protected $primaryKey = 'ma_tuy_chon';
    protected $fillable = ['ma_nhom_tuy_chon', 'ten_tuy_chon'];

    public function nhom_tuy_chon()
    {
        return $this->belongsTo(nhom_tuy_chon::class, 'ma_nhom_tuy_chon');
    }
}
