<?php

namespace App\Http\Controllers;

use App\Models\nha_cung_cap;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class nha_cung_capController extends Controller
{
    //

    public function danh_sach_NCC() {
        $nhacungcap = nha_cung_cap::all();
        return view('sanpham.nha-cung-cap', ['nhacungcap' => $nhacungcap]);
    }

    public function layNhaCungCap()
    {
        $ncc=nha_cung_cap::all();
        return response()->json($ncc);
    }



    public function themNCC(Request $request){
        $request->validate([
            'ten_nha_cung_cap' => 'required|string|max:255',
            'dia_chi' => 'nullable|string',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100|unique:nha_cung_cap,email',
        ]);

        $ncc= new nha_cung_cap();
        $ncc->ten_nha_cung_cap= $request->ten_nha_cung_cap;
        $ncc->dia_chi= $request->dia_chi;
        $ncc->so_dien_thoai= $request->so_dien_thoai;
        $ncc->email= $request->email;

        if($ncc->save()) {
            return response()->json([
                'message' => 'Thêm nhà cung cấp thành công',
                'nha_cung_cap' => $ncc,
            ], 201); // 201 là mã phản hồi cho "Created"
        } else {
            return response()->json(['message' => 'Không thể thêm nhà cung cấp'], 500);
        }
    }
    public function xoaNCC($id) {
        // Tìm nhà cung cấp theo ID
        $ncc = nha_cung_cap::find($id);
    
        // Kiểm tra nếu nhà cung cấp không tồn tại
        if (!$ncc) {
            return response()->json(['message' => 'Nhà cung cấp không tồn tại'], 404);
        }
    
        // Xóa nhà cung cấp
        $ncc->delete();
    
        return response()->json(['message' => 'Nhà cung cấp đã được xóa thành công'], 200);
    }
    
    public function suaNCC(Request $request) {
        // Tìm nhà cung cấp theo ID
        $ncc = nha_cung_cap::find($request->ma_nha_cung_cap);
    
        // Kiểm tra nếu nhà cung cấp không tồn tại
        if (!$ncc) {
            return response()->json(['message' => 'Nhà cung cấp không tồn tại'], 404);
        }
    
        // Cập nhật thông tin nhà cung cấp
        $ncc->ten_nha_cung_cap = $request->input('ten_nha_cung_cap', $ncc->ten_nha_cung_cap);
        $ncc->dia_chi = $request->input('dia_chi', $ncc->dia_chi);
        $ncc->so_dien_thoai = $request->input('so_dien_thoai', $ncc->so_dien_thoai);
        $ncc->email = $request->input('email', $ncc->email);
    
        // Lưu thay đổi
        $ncc->save();
    
        return redirect()->back();
    }
        
}
