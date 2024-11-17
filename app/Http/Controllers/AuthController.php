<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function dangKy(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|unique:user',
            'mat_khau' => 'required|min:6',
            'ho_ten' => 'required',
            'email' => 'required|email|unique:user',
        ]);

        $user = new User();
        $user->ten_dang_nhap = $request->ten_dang_nhap;
        $user->mat_khau = Hash::make($request->mat_khau);
        $user->ho_ten = $request->ho_ten;
        $user->email = $request->email;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function dangNhap(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required',
            'mat_khau' => 'required',
        ]);

        $user = User::where('ten_dang_nhap', $request->ten_dang_nhap)->first();

        if (!$user || !Hash::check($request->mat_khau, $user->mat_khau)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        Auth::guard('user')->login($user);
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ], 200);
    }


    
    public function dangXuat(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }


}
