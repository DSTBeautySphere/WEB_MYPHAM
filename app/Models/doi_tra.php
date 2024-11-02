<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doi_tra extends Model
{
    use HasFactory;

    protected $table = 'doi_tra';
    protected $primaryKey = 'ma_doi_tra';
    protected $fillable = ['ma_don_dat', 'ma_bien_the', 'ly_do_doi_tra', 'ngay_yeu_cau', 'trang_thai'];
    public $timestamps = false;


    public function don_dat()
    {
        return $this->belongsTo(don_dat::class, 'ma_don_dat');
    }

    public function san_pham()
    {
        return $this->belongsTo(bien_the_san_pham::class, 'ma_bien_the');
    }
}
