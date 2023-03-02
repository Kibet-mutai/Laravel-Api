<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Index home
     * @OA\Get (
     *     path="/api/products",
     *     tags={"Index"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="price", type="string", example="price"),
     *              @OA\Property(property="quantity", type="string", example="quantity"),
     *              @OA\Property(property="category_id", type="string", example="category_id"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="image", type="string", example="image"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     ),
     * )
     */
    public function index(){
        $Index = Product::paginate(6);
        $stores = Store::all();
        return response()->json([
            'store'=>$stores,
            'Index'=>$Index
        ]);
    }



    /**
     * Get Index details
     * @OA\Get (
     *     path="/api/products/{id}",
     *     tags={"Index"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="price", type="string", example="price"),
     *              @OA\Property(property="quantity", type="string", example="quantity"),
     *              @OA\Property(property="category_id", type="string", example="category_id"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="image", type="string", example="image"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *         response=201,
     *         description="Unavailable",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="not found!")
     *         )
     *     )
     * )
     */
    public function show($id){
        $Index = Product::findOrFail($id);
        return response()->json([
            'Index'=>$Index
        ]);
    }


    public function search_product(Request $request)
    {
        try
        {
            $search = $request->input('search');
            $Product = Product::where('name', 'like', '%' . $search . '%')
                                ->orWhere('description', 'like', '%' . $search . '%')
                                ->get();

            if (!$Product->count()) {
                throw new \Exception("No results found for the search query.");
            }

            return response()->json(['Product' => $Product]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function filter(Request $request)
    {
        try {
            $category = $request->input('category');
            $Product = Product::whereHas('category', function ($query) use ($category) {
                $query->where('category', $category);
            })->get();

            return response()->json([
                'status' => 'success',
                'data' => $Product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Not Found!',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
