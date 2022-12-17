<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Products;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //create order

    public function order(Request $request) {
        if (auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $quantity = $request->quantity;
            $product_check = Products::where('id', $product_id)->first();
            if($product_check){

                if(Order::where('product_id', $product_id)->where('user_id', $user_id)->exists()){
                    return response()->json([
                        'status'=>201,
                        'message'=>$product_check->name. 'Product already in the Cart'
                    ]);
                }
                else
                {
                    $item = new Order;
                    $item->user_id = $user_id;
                    $item->product_id = $product_id;
                    $item->quantity = $quantity;
                    $item->save();
                    return response()->json([
                        'status'=>201,
                        'message'=>$product_check->name. 'added to cart succcessfully'
                ]);
                
                }
            }

        }
        
        else {
            return response()->json([
                'status'=>401,
                'message'=>'Sign in to order'
            ]);
           }
    }


    public function show() {
        if (auth('sanctum')->check()) {
            return Order::latest()->get();
        }
        
    }

}
   

