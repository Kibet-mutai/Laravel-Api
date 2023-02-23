<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function place_order(Request $request) {
        if(auth()->check()){
            $data = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_no' => 'required|unique:orders,phone_no,except,id',
                'email' => 'required|email:unique',
                'zipcode' => 'required',
                'sub_county' => 'required',
                'county' => 'required',
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized!'
            ]);
        }

        $order = new Order;
        $order->user_id = auth()->user()->id;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->phone_no = $request->phone_no;
        $order->email = $request->email;
        $order->zipcode = $request->zipcode;
        $order->first_name = $request->first_name;
        $order->sub_county = $request->sub_county;
        $order->county = $request->county;
        $order->payment_method = 'paypal';
        $order->tracking_no = 'store'.rand(1000, 99999);

        $order->save();

        $cart = Cart::where('user_id', auth()->id())->get();
        $order_items = [];
        foreach($cart as $item) {
            $order_items[] = [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ];

            $item->product->update([
                'qauntity' => $item->product->qauntity - $item->qauntity
            ]);
        }

        $order->order_items()->createMany($order_items);
        Cart::destroy($cart);

        return response()->json([
            'message' => 'successfully ordered',
            'data' => $data,
            'order' => $order,
            'items' => $order_items
        ]);
    }
}
