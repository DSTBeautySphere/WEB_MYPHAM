<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\bien_the_san_pham;
use App\Models\san_pham;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            // $products = bien_the_san_pham::join('san_pham', 'bien_the_san_pham.ma_san_pham', '=', 'san_pham.ma_san_pham')
            //             ->where('san_pham.ma_loai_san_pham', $id)
            //             ->select('bien_the_san_pham.*')
            //             ->with(['san_pham.loai_san_pham', 'san_pham.anh_san_pham'])
            //             ->get();

            $products = san_pham::with(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham'])
                ->where('ma_loai_san_pham', $id)
                ->get();

            return response()->json($products);
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
        //
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
}