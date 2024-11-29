<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\gio_hang;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $userId = $request->userId;
            
            $carts = gio_hang::where('ma_user', $userId)->with([
                'bien_the_san_pham.san_pham.anh_san_pham',
                'bien_the_san_pham.san_pham'
            ])->get();

            return response()->json($carts);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
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
            $userId = $request->userId;
            $productId = $request->productId;
            $quantity = $request->quantity;
            $price = $request->price;

            $cartExist = gio_hang::where('ma_user', $userId)->where('ma_bien_the', $productId)->first();

            if($cartExist){
                $cartExist->so_luong += $quantity;
                $cartExist->gia_ban += $price;
                $cartExist->save();
            }else{
                gio_hang::create([
                    'ma_user' => $userId,
                    'ma_bien_the' => $productId,
                    'so_luong' => $quantity,
                    'gia_ban' => $price,
                    'ngay_tao' => now(),
                ]);
            }

            $carts = gio_hang::where('ma_user', $userId)->with([
                'bien_the_san_pham.san_pham.anh_san_pham',
                'bien_the_san_pham.san_pham'
            ])->get();

            return response()->json($carts);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
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
        try {
            $cart = gio_hang::find($id);
            $cart->delete();
            return response()->json($cart);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}