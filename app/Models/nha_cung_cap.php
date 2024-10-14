<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nha_cung_cap extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_nha_cung_cap';
    protected $table = 'nha_cung_cap';
    
    protected $fillable = [
        'ten_nha_cung_cap',
        'dia_chi',
        'so_dien_thoai',
        'email',
        'created_at',
        'updated_at'
    ];

    public function san_pham()
    {
        return $this->hasMany(san_pham::class, 'ma_nha_cung_cap', 'ma_nha_cung_cap');
    }
}
