<?php

namespace App\Http\Controllers;


use App\Models\nhom_tuy_chon;
use App\Models\tuy_chon;
use Illuminate\Http\Request;


class tuy_chonController extends Controller
{
    //
    public function layNhomTuyChon()
    {
    
        $tuyChon = nhom_tuy_chon::with('tuy_chon')->get();

        return response()->json($tuyChon);
    }
    public function showForm()
    {
        $nhomTuyChon = nhom_tuy_chon::all(); // Lấy tất cả nhóm tùy chọn từ cơ sở dữ liệu
        return view('sanpham.them-san-pham', ['nhomTuyChon'=> $nhomTuyChon]);
    }

    public function getTuyChonByNhom(Request $request)
    {
        // Lấy tất cả tùy chọn của nhóm
        $tuyChon = tuy_chon::where('ma_nhom_tuy_chon', $request->ma_nhom_tuy_chon)->get();
        
        return response()->json($tuyChon);
    }

    public function themTuyChon(Request $request){
        $request->validate([
            'ma_nhom_tuy_chon' => 'required|integer',
            'ten_tuy_chon' => 'required|string|max:255',
        ]);

        $tuyChon= tuy_chon::create([
            'ma_nhom_tuy_chon'=> $request->ma_nhom_tuy_chon,
            'ten_tuy_chon'=>$request->ten_tuy_chon
        ]);
        return redirect()->back();
    }

    public function suaTuyChon(Request $request){
        $request->validate([
            'ma_nhom_tuy_chon' => 'required|integer',
            'ten_tuy_chon' => 'required|string|max:255',
        ]);

        $tuyChon= tuy_chon::find($request->ma_tuy_chon);
        if($tuyChon){

            $tuyChon->update([
                'ma_nhom_tuy_chon'=> $request->ma_nhom_tuy_chon,
                'ten_tuy_chon'=>$request->ten_tuy_chon    
            ]);

            return redirect()->back();
        }
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy tùy chọn.'
        ], 404);
    }
}
