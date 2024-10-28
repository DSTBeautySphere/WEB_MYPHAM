<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class yeu_thich extends Model
{
    use HasFactory;

    protected $table = 'yeu_thich';
    protected $primaryKey = 'ma_yeu_thich';
    protected $fillable = ['ma_user', 'ma_san_pham', 'ngay_tao'];
    public $incrementing = false;
    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user');
    }

    public function san_pham()
    {
        return $this->belongsTo(san_pham::class, 'ma_san_pham');
    }
}
