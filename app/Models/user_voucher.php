<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_voucher extends Model
{
   
    use HasFactory;

    protected $table = 'user_voucher';
    protected $primaryKey = 'ma_user_voucher';

    public $timestamps = false;

    protected $fillable = [
        'ma_user',
        'ma_voucher',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ma_user', 'ma_user');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'ma_voucher', 'ma_voucher');
    }
}
