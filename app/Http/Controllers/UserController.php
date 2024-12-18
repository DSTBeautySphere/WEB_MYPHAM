<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    //
    public function showUser(){
        // $users= User::all();
        // return view("khachHang.user-management", compact('users'));
        $users = User::paginate(4); 
        return view("khachHang.user-management", compact('users'));
    }

    public function LocUser(Request $request)
    {
        Log::info('Lọc dữ liệu:', $request->all());
        $query = User::query();

       
        if ($request->has('status') && $request->status !== '') {
            $query->where('trang_thai', $request->status);
        }
        
       


        if ($request->status === null) {
            $users = User::paginate(4);  
        } else {
            $users = $query->paginate(4); 
        }
        // $users = $query->paginate(3); // 10 dòng mỗi trang

        // if ($request->ajax()) {
        //     return response()->json($users);
        // }
    
        // return view('khachHang.user-management', compact('users'));

        // return response()->json($users);
        return view("khachHang.user-management", compact('users'));
    }
    public function timKiem(Request $request){
        $query = User::query();
        if ($request->has('name') && $request->name) {
            $query->where('ho_ten', 'like', '%' . $request->name . '%');

        }
        
        if ($request->name === null) {
            $users = User::paginate(4); 
        } else {
            $users = $query->paginate(4); 
        }
        // $users = $query->paginate(3); // 10 dòng mỗi trang

        // if ($request->ajax()) {
        //     return response()->json($users);
        // }
    
        return view('khachHang.user-management', compact('users'));
        //return response()->json($users);
    }

    public function thongTinUser($userId)
    {
        // Lấy thông tin người dùng và các đơn đặt của họ
        $user = User::with('don_dat')->findOrFail($userId);

        return response()->json($user);
    }
    public function updateStatus($id, Request $request)
    {
        // Lấy người dùng theo ID
        $user = User::find($id);

        if ($user) {
            // Cập nhật trạng thái người dùng
            $user->trang_thai = $request->status === 'active' ? 1 : 0;
            $user->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
}
}
