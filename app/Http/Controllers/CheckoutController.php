<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function place_order(Request $request) {
        $customer = Auth::user();
        if ($customer->customer) {
            return response()->json([
                'error' => 'Already Placed order'
            ]);
        }
        if(auth()->check()){
            $order = new Order;
            $order->customer_id= auth()->user()->id;
            $order->payment_id = $request->payment_id;
            $order->tracking_no = 'store'.rand(1000, 99999);

            $order->save();

            $cart = Cart::where('customer_id', auth()->id())->get();
            $order_items = [];
            $total_price = 0;
            foreach($cart as $item) {
                $product = $item->product;
                print('product: ');
                print_r($product);

                if (!$item->product) {
                    print('product not found');
                    continue;

                }
                dd($item->product);
                $order_items[] = [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $product->price
                ];
                $item_total_price = $product->price * $item->quantity;
                $total_price += $item_total_price;
                print('item total price: '.$item_total_price);
                print('total price: '.$total_price);
                $item->product->update([
                    'quantity' => $product->quantity - $item->quantity
                ]);
                print('updated quantity: '.$product->quantity);
                $total_price = $order->product->price * $item->quantity;
        }

        $order->order_items()->createMany($order_items);
        Cart::destroy($cart);

        return response()->json([
            'message' => 'successfully ordered',
            'order' => $order,
            'items' => $order_items,
            'total price' => $total_price,

        ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized!'
            ]);
        }


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
