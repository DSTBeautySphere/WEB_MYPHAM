<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function dangKy(Request $request){
        $request->validate(
            [
                'ten_dang_nhap'=>'required',
                'mat_khau'=>'required',
                'ho_ten'=>'required',
                'email'=>'required',
            ]);
        $user= new User();
        $user->ten_dang_nhap= $request->ten_dang_nhap;    
        $user->mat_khau = Hash::make($request->mat_khau);
        $user->ho_ten = $request->ho_ten;
        $user->email = $request->email;
        $user->so_dien_thoai = $request->so_dien_thoai;
        $user->dia_chi = $request->dia_chi;
        $user->gioi_tinh = $request->gioi_tinh;
        $user->phan_quyen = 'Khach hang';
        $user->save();
        return response()->json(['message' => 'User registered successfully'], 201);
    }
    public function dangNhap(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required',
            'mat_khau' => 'required|string',
        ]);

        $credentials = $request->only('ten_dang_nhap', 'mat_khau');

        if (Auth::attempt(['ten_dang_nhap' => $credentials['ten_dang_nhap'], 'password' => $credentials['mat_khau']])) {
            $user = Auth::user();
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
            ], 200);
        } else {
            return response()->json(['message' => 'Dang nhap khong thanh cong'], 401);
        }
    }

    
    public function dangXuat()
    {
        Auth::logout();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }

}
