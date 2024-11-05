<?php

namespace App\Http\Controllers;

use App\Models\chi_tiet_don_dat;
use Illuminate\Http\Request;

class chi_tiet_don_datController extends Controller
{
    //
    public function themChiTietDonDat(Request $request)
    {
        $chiTietDonDat = chi_tiet_don_dat::create([
            'ma_don_dat' => $request->ma_don_dat,
            'ma_bien_the' => $request->ma_bien_the,
            'so_luong' => $request->so_luong,
            'gia_ban' => $request->gia_ban,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $chiTietDonDat
        ], 201);
    }

}
