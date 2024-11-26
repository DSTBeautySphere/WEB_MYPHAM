<?php

namespace App\Http\Controllers;

use App\Models\bien_the_san_pham;
use App\Models\khuyen_mai_san_pham;
use App\Models\loai_san_pham;
use App\Models\san_pham;
use Illuminate\Http\Request;

class khuyen_mai_san_phamController extends Controller
{
    //
    public function showDanhSachSP(){
        $products=san_pham::with("khuyen_mai_san_pham")->get();
        return view('KhuyenMai.quan-ly-khuyen-mai',compact('products')); 
    }

   

}
