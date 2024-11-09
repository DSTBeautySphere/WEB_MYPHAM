<?php

namespace App\Http\Controllers;


use App\Models\nhom_tuy_chon;
use Illuminate\Http\Request;

class tuy_chonController extends Controller
{
    //
    public function layNhomTuyChon()
    {
    
        $tuyChon = nhom_tuy_chon::with('tuy_chon')->get();

        return response()->json($tuyChon);
    }

}
