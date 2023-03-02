<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
        $products = Product::paginate(6);
        $stores = Store::all();
        return response()->json([
            'store'=>$stores,
            'products'=>$products
        ]);
    }

    public function show($id){
        $products = Product::findOrFail($id);
        return response()->json([
            'products'=>$products
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
