<?php

namespace App\Http\Controllers;

use App\Models\loai_san_pham;
use App\Models\nha_cung_cap;
use App\Models\tuy_chon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index()
    {
        return view('sanpham.them-san-pham');
    }
    //them-san-pham
    public function showThemSanPham()
    {
        $loaiSanPham=loai_san_pham::all();
        $nhaCungCap=nha_cung_cap::all();
        $tuyChon=tuy_chon::all();
        return view('sanpham.them-san-pham',['loaiSanPham'=>$loaiSanPham,'nhaCungCap'=>$nhaCungCap,'tuyChon'=>$tuyChon]);
    }
    
}
