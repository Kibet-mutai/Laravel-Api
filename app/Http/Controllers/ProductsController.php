<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seller;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller = auth()->user()->id;
        $seller_id = Seller::findOrFail($seller);
        if ($seller_id->user_id != auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
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
            'category_id' => 'required|exists:category,id',
            'description'=> 'required',
            'image'=> 'required',
            'quantity'=> 'required',
        ]);

        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->image = $request->image;
        $product->seller_id = auth()->user()->id;
        $product->save();
        // Product::create($data);

        return response()->json(['message'=>'Product created!','data'=>$data,]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $seller = auth()->user()->id;
        $seller_id = Seller::findOrFail($seller);
        if ($seller_id->user_id != auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
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
        $seller = auth()->user()->id;
        $seller_id = Seller::findOrFail($seller);
        if ($seller_id->user_id != auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
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
        $seller = auth()->user()->id;
        $seller_id = Seller::findOrFail($seller);
        if ($seller_id->user_id != auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(['message'=>'Product deleted!']);
    }

}

