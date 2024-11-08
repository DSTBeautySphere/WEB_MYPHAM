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

    public function them_dong_san_pham(Request $request){
        $request->validate([
            'ten_dong_san_pham' => 'required|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
        ]);
        try{
            $dongSP= dong_san_pham::create([
                'ten_dong_san_pham'=>$request->ten_dong_san_pham,
                'mo_ta'=>$request->mo_ta
            ]);   
            return response()->json([
                'success' => true,
                'message' => 'Dòng sản phẩm đã được thêm thành công.',
                'data' => $dongSP
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm dòng sản phẩm: ' . $e->getMessage()
            ]);
        }
    }

    public function sua_dong_san_pham(Request $request){
        $request->validate([
            'ten_dong_san_pham' => 'required|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
        ]);

        try{
            $dongSP= dong_san_pham::find($request->ma_dong_san_pham);
            $dongSP->update([
                'ten_dong_san_pham'=>$request->ten_dong_san_pham,
                'mo_ta'=>$request->mo_ta
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Dòng sản phẩm đã được cập nhật thành công.',
                'data' => $dongSP
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật dòng sản phẩm: ' . $e->getMessage()
            ]);
        }
    }

    public function xoa_dong_san_pham(Request $request){
        try{
            $dongSP= dong_san_pham::find($request->ma_dong_san_pham);
            $dongSP->delete();
            return response()->json([
                'success' => true,
                'message' => 'Dòng sản phẩm đã được xóa thành công.',
                'data' => $dongSP
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa dòng sản phẩm: ' . $e->getMessage()
            ]);
        }
    }
}
