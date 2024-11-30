<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\bien_the_san_pham;
use App\Models\loai_san_pham;
use App\Models\san_pham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $products = san_pham::with(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham', 'khuyen_mai_san_pham'])
                        ->where('ma_loai_san_pham', $id)
                        ->get();

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // public function getAll(Request $request)
    // {
    //     try {
    //         $categoriesId = $request->input('categories');

    //         $query = san_pham::with(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham', 'khuyen_mai_san_pham']);

    //         if (!empty($categoriesId) && is_array($categoriesId)) {
    //             $query->whereIn('ma_loai_san_pham', $categoriesId);
    //         }

    //         $products = $query->get();

    //         return response()->json($products);
    //     } catch (\Exception $e) {
    //         return response()->json($e->getMessage());
    //     }
    // }

    public function getAll(Request $request)
    {
        try {
            $categoriesId = $request->input('categories');

            $query = san_pham::with([
                'loai_san_pham',
                'anh_san_pham',
                'bien_the_san_pham',
                'khuyen_mai_san_pham',
            ])
            ->withCount(['danh_gia as so_sao_trung_binh' => function ($query) {
                $query->select(DB::raw('ROUND(AVG(so_sao), 0)')); // Làm tròn nửa lên
            }]);

            if (!empty($categoriesId) && is_array($categoriesId)) {
                $query->whereIn('ma_loai_san_pham', $categoriesId);
            }

            $products = $query->get();

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getAllCategories()
    {
        try {
            $categories = loai_san_pham::all();

            return response()->json($categories);
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
        try {
            $product = san_pham::with(['loai_san_pham', 'anh_san_pham', 'bien_the_san_pham', 'khuyen_mai_san_pham', 'mo_ta', 'mo_ta.chi_tiet_mo_ta'])
                ->where('ma_san_pham', $id)->withCount(['danh_gia as so_sao_trung_binh' => function ($query) {
                    $query->select(DB::raw('ROUND(AVG(so_sao), 0)')); // Làm tròn nửa lên
                }])
                ->first();

            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
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