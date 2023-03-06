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

        } else {
            return response()->json([
                'message' => 'Unauthorized!'
            ]);
        }

        $order = new Order;
        $order->customer_id= auth()->user()->id;
        $order->payment_id = $request->payment_id;
        $order->tracking_no = 'store'.rand(1000, 99999);

        $order->save();

        $cart = Cart::where('customer_id', auth()->id())->get();
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
            $total_price = $item->product->price * $item->qauntity;
        }

        $order->order_items()->createMany($order_items);
        Cart::destroy($cart);

        return response()->json([
            'message' => 'successfully ordered',
            'order' => $order,
            'items' => $order_items,
            'total price' => $total_price
        ]);
    }



    public function cancel_order($id){
        $user_id = auth()->user()->id;
        $user= Auth::findOrFail($user_id);
        $order = Order::findOrFail($id);
        if ($user->user_id !=auth()->user()->id){
            return response()->json(['error' => 'Unauthorized access'], 401);
        } else {
            Order::destroy($order);
            return response()->json(['message' => 'Order deleted successfully']);

        }
    }
}
