<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function sales(Request $request) {
        $sales = $request->input('product_price');
        // dd($sales);
        $quantity = $request->input('order_quantity');
        if ($request->input('is_delivered') === 1){
            $total_sales = $quantity * $sales;
            return $total_sales;
        } else{
            return response()->json(['message'=>'No sales this week!']);
        }
    }



    public function users(Request $request) {

    }


    public function in_stock(Request $request, $id) {
        $product_id = $request->product_id;
        // dd($product_id);
        $quantity = $request->quantity;
        $delivered = $request->is_delivered;
        $product_check = Product::where('id', $product_id)->first();
        dd($product_check);
        $delivery_check = Order::where('is_delivered', $delivered)->first();
        $quantity_check = Product::where('quantity', $quantity)->first();
        // dd($quantity);
        foreach((array)$quantity_check as $quantities) {
            if ($product_check && $delivery_check === 1){
                $in_stock = $quantities-1;
                return $in_stock;
                // dd($in_stock);
                if($in_stock<0) {
                    // echo 'not available';
                    return response()->json(['message' => "You're' currently running out this product!"]);
                }

            } else {
                return response()->json(['message' => "you still have this product in store"]);
            }
        }
    }




    public function check(Request $request, $delivery){
        $delivery = Order::find('is_delivered');

        // If the delivery does not exist, return a 404 error
        if (!$delivery) {
            abort(404, "Delivery not found");
        }

        // Retrieve the products that were delivered in this delivery
        $delivered_products = $delivery->products;

        // Retrieve the total quantity of each product that was delivered
        $delivered_product_quantities = $delivered_products->pluck('pivot.quantity', 'id');

        // Calculate the remaining quantity of each product in stock
        $remaining_products = [];
        dd($remaining_products);
        foreach ($delivered_product_quantities as $product_id => $quantity) {
            $product = Product::find($product_id);
            $remaining_quantity = $product->quantity - $quantity;
            $remaining_products[] = [
                'product_name' => $product->name,
                'remaining_quantity' => $remaining_quantity,
            ];
        }

        // Display the remaining products in a view
        return view('remaining-products', [
            'delivery' => $delivery,
            'remaining_products' => $remaining_products,
        ]);
    }
}
