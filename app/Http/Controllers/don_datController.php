<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\don_dat;

class don_datController extends Controller
{
    //
    public function themDonDat(Request $request)
    {
        $donDat = don_dat::create([
            'ma_user' => $request->ma_user,
            'ngay_dat' => $request->ngay_dat,
            'tong_tien' => $request->tong_tien,
            'trang_thai' => $request->trang_thai,
        ]);

        

        return response()->json($donDat);
    }

}
