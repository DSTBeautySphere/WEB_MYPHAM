<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doi_tra extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_doi_tra';
    protected $table = 'doi_tra';
    
    protected $fillable = [
        'ma_don_dat',
        'ly_do',
        'trang_thai',
        'created_at',
        'updated_at'
    ];

    public function don_dat()
    {
        return $this->belongsTo(don_dat::class, 'ma_don_dat', 'ma_don_dat');
    }
}
