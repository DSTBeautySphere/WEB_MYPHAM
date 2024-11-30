<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            if(User::where('ten_dang_nhap', $request->username)->exists()) {
                return response()->json(['message' => 'Tên đăng nhập đã tồn tại'], 400);
            }

            $user = new User();
            $user->ten_dang_nhap = $request->username;
            $user->mat_khau = Hash::make($request->password);
            $user->ho_ten = $request->fullName;
            $user->email = $request->email;
            $user->save();

            return response()->json([
                'isSuccess' => true
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $user = User::where('ten_dang_nhap', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->mat_khau)) {
                return response()->json(['message' => 'Thông tin đăng nhập không hợp lệ'], 401);
            }

            return response()->json([
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function checkUsername(Request $request)
    {
        try {
            $user = User::where('ten_dang_nhap', $request->username)->first();

            return response()->json([
                'isSuccess' => $user ? true : false
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $user = User::where('ten_dang_nhap', $request->username)->first();

            $user->mat_khau = Hash::make($request->password);
            $user->save();

            return response()->json([
                'isSuccess' => true
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}