<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vi_dien_tu extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_vi';
    protected $table = 'vi_dien_tu';
    
    protected $fillable = [
        'ma_user',
        'so_du',
        'ngay_cap_nhat',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user', 'ma_user');
    }
}
