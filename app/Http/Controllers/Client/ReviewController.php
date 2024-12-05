<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\danh_gia;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $reviews = danh_gia::with('user')->where('ma_san_pham', $id)->get();

            return response()->json($reviews);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $review = danh_gia::create($request->all());

            return response()->json($review);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checkUserPurchasedProduct(Request $request)
    {
        // Lấy dữ liệu từ request
        $userId = $request->input('ma_user');
        $productId = $request->input('ma_san_pham');

        // Tìm User
        $user = User::with('don_dat.chi_tiet_don_dat')->find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User không tồn tại.',
            ], 404);
        }

        // Kiểm tra lịch sử mua hàng của User
        $hasPurchased = false;
        foreach ($user->don_dat as $donDat) {
            foreach ($donDat->chi_tiet_don_dat as $chiTiet) {
                if ($chiTiet->bien_the_san_pham->ma_san_pham == $productId) {
                    $hasPurchased = true;
                    break 2; // Thoát cả 2 vòng lặp
                }
            }
        }

        // Trả về kết quả
        return response()->json([
            
                 $hasPurchased,
            
        ], 200);
    }
}