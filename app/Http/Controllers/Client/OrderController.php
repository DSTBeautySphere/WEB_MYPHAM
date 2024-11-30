<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\don_dat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = DB::table('don_dat')
                ->where('don_dat.ma_user', $id)
                ->get();

            return response()->json($order);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function detail(string $id)
    {
        try {
            $order = don_dat::with(['chi_tiet_don_dat', 'chi_tiet_don_dat.bien_the_san_pham', 'chi_tiet_don_dat.bien_the_san_pham.san_pham', 'chi_tiet_don_dat.bien_the_san_pham.san_pham.anh_san_pham'])
                    ->where('ma_don_dat', $id)->first();

            return response()->json($order);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $order = don_dat::where('ma_don_dat', $id)->first();
            $order->trang_thai_thanh_toan = "DA_THANH_TOAN";
            $order->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
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
}