<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class san_pham extends Model
{
    use HasFactory;
    protected $primaryKey = 'ma_san_pham';
    protected $table = 'san_pham';
    
    protected $fillable = [
        'ma_loai_san_pham',
        'ma_nha_cung_cap',
        'ten_san_pham',
        'trang_thai'
    ];
    public $timestamps = false;


    public function bien_the_san_pham()
    {
        return $this->hasMany(bien_the_san_pham::class,'ma_san_pham', 'ma_san_pham');
    }

    public function chi_tiet_don_dat()
{
    return $this->hasManyThrough(chi_tiet_don_dat::class, bien_the_san_pham::class, 'ma_san_pham', 'ma_bien_the', 'ma_san_pham', 'ma_bien_the');
}


    public function loai_san_pham()
    {
        return $this->belongsTo(loai_san_pham::class, 'ma_loai_san_pham', 'ma_loai_san_pham');
    }

    public function nha_cung_cap()
    {
        return $this->belongsTo(nha_cung_cap::class, 'ma_nha_cung_cap', 'ma_nha_cung_cap');
    }

    public function khuyen_mai_san_pham()
    {
        return $this->hasMany(khuyen_mai_san_pham::class, 'ma_san_pham', 'ma_san_pham');
    }
  
    public function anh_san_pham()
    {
        return $this->hasMany(anh_san_pham::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function deleteExpiredDiscounts()
    {
        $currentDate = Carbon::now(); // Lấy ngày hiện tại

        // Lấy tất cả các khuyến mãi của sản phẩm
        $khuyenMai = $this->khuyen_mai_san_pham;

        foreach ($khuyenMai as $discount) {
            $discountEndDate = Carbon::parse($discount->ngay_ket_thuc); // Giả sử 'ngay_ket_thuc' là ngày kết thúc của khuyến mãi

            if ($discountEndDate->lt($currentDate)) {
                // Nếu ngày kết thúc nhỏ hơn ngày hiện tại, xóa khuyến mãi
                $discount->delete();
            }
        }
    }

    public function mo_ta()
    {
        return $this->hasMany(mo_ta::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function danh_gia()
    {
        return $this->hasMany(danh_gia::class, 'ma_san_pham', 'ma_san_pham');
    }

}
