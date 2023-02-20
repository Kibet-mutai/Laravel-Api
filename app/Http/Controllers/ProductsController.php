<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::paginate(6);
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
        Product::create($data);

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
        $product = Product::findOrFail($id);
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
        $Product = Product::findOrFail($id);
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
        $Product->update($data);
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
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(['message'=>'Product deleted!']);
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

