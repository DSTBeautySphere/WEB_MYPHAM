<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dong_san_pham;
use App\Models\loai_san_pham;
use GuzzleHttp\Psr7\Response;

class dong_san_phamController extends Controller
{
    //
    public function view_dongsanpham()
    {
        return view('dongsanpham');
    }
    
    public function lay_dong_san_pham()
    {
        $dongsanpham=dong_san_pham::with(['loai_san_pham'])->get();
        return Response()->json($dongsanpham);
    }
}
