<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\san_pham;


class TestController extends Controller
{
    //
    public function tat_ca_san_pham()
    {
        $sanPhams = san_pham::with(['loai_san_pham', 'nha_cung_cap', 'khuyen_mai_san_pham', 'product_images'])->get();
        dd($sanPhams->toArray());
    }
}
