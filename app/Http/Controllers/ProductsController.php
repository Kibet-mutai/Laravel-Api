<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Products::paginate(6);
        return response()->json(['data'=>$product]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name'=>'required',
            'price'=> 'required',
            'description'=> 'required',
            'image'=> 'required',
            'quantity'=> 'required',
        ]);

        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        Products::create($data);

        return response()->json(['message'=>'Product created!','data'=>$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $product = Products::findOrFail($id);
        return response()->json(['data'=>$product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Products = Products::findOrFail($id);
        $data = $request->validate([
            'name'=>'required',
            'price'=> 'required',
            'description'=> 'required',
            'image'=> 'required',
            'quantity'=> 'required',
        ]);

        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        $Products->update($data);
        return response()->json(['message'=>'Product updated!','data'=>$data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        $product->delete();

        return response()->json(['message'=>'Product deleted!']);
    }



    public function search_products(Request $request)
    {
        try
        {
            $search = $request->input('search');
            $products = Products::where('name', 'like', '%' . $search . '%')
                                ->orWhere('description', 'like', '%' . $search . '%')
                                ->get();

            if (!$products->count()) {
                throw new \Exception("No results found for the search query.");
            }

            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function filter(Request $request)
    {
        try {
            $category = $request->input('category');
            $Products = Products::whereHas('category', function ($query) use ($category) {
                $query->where('category', $category);
            })->get();

            return response()->json([
                'status' => 'success',
                'data' => $Products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Not Found!',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

