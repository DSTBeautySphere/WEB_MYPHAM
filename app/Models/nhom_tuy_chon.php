<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nhom_tuy_chon extends Model
{
    use HasFactory;
    protected $table = 'nhom_tuy_chon';
    protected $primaryKey = 'ma_nhom_tuy_chon';
    protected $fillable = ['ten_nhom_tuy_chon'];
    public $timestamps = false;

}
