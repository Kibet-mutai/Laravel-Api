<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //create Cart

    public function add_to_cart(Request $request) {
        $data = $request->validate([
            'quantity' => 'required',
            'product_id' => 'required|exists:products,id'
        ]);
        $data['user_id']=Auth::user()->id;
        $user = auth()->user()->id;
        $product_id = $request->product_id;
        $product_check = Product::where('id', $product_id)->first();
        if($product_check) {
            if(Cart::where('product_id', $product_id)->where('user_id', $user)->exists()){
                return response()->json([
                    'status'=>201,
                    'message'=> $product_check->name. ' already in the Cart'
                ]);
        }}
        if($product_check===0){
            response()->json([
                'status'=>201,
                'message'=>'Product is out of stock'
            ]);
        }
        Cart::create($data);
        return response()->json([
            'message' => $product_check->name. ' added to cart successful',
        ], 200);

    }



    public function update_cart(Request $request, $id) {
        $user_id = auth()->user()->id;
        $user = User::findOrFail($user_id);
        if($user->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Unauthorized!'
            ],401);
        }
        $cart_item = Cart::findOrFail($id);
        $data = $request->validate([
            'quantity' => 'required',
            'product_id' => 'required|exists:products,id'
        ]);
        $cart_item->update($data);
        return response()->json([
            'message' => 'Cart updated successfully!'
        ]);
    }



    public function cart_detail(Request $request, $id) {
        $user_id = auth()->user()->id;
        $user = User::findOrFail($user_id);
        if($user->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Unauthorized!'
            ],401);
        }
        $cart_items = Cart::findOrFail($id);
        $total_price = $request->input('price') * $request->input('quantity');
        return response()->json([
            'cart_items' => $cart_items,
            'total' => $total_price
        ]);
    }



    public function delete_cart($id){
        $user_id = auth()->user()->id;
        $user = User::findOrFail($user_id);
        // dd($user);
        if($user->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Unauthorized!'
            ], 401);
        }
        $cart_items = Cart::findOrFail($id);
        $cart_items->delete();
        return response()->json([
            'message' => 'Cart deleted!'
        ]);
    }
}


