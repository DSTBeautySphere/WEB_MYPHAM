<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vi_dien_tu extends Model
{
    use HasFactory;

    protected $table = 'vi_dien_tu';
    protected $primaryKey = 'ma_vi';
    protected $fillable = ['ma_user', 'so_du', 'ngay_cap_nhat'];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user');
    }
}
