<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Get Storedetails
     * @OA\Get (
     *     path="/api/store/{id}",
     *     tags={"Store"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="product_id", type="string", example="product_id"),
     *              @OA\Property(property="product_qty", type="string", example="product_qty"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized!")
     *         )
     *     )
     * )
     */
    public function show($id) {
        $seller_id = auth()->user()->id;
        $seller = Seller::findOrFail($seller_id);
        if ($seller->user_id != auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }

        $store = Store::findOrFail($id);
        $products = Product::where('seller_id', auth()->id())->get();
        $store_items = [];
        foreach($products as $p) {
            $store_items[] = [
                'product_id' => $p->product_id,
                'image' => $p->image,
                'price' => $p->price,
                'quantity' => $p->quantity,
                'name' => $p->name
            ];}
        return response()->json([
            'store'=>$store,
            'store_items' => $store_items
        ]);
    }



    /**
     * Create Store
     * @OA\Post (
     *     path="/api/create/store",
     *     tags={"Store"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"example name",
     *                     "name":"example description",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated"),
     *          )
     *      )
     * )
     */



    public function create_store(Request $request){
        $data = $request->validate([
            'name'=>'required|max:255|string',
            'description'=>'required'
        ]);

        $store = new Store;
        $store->name = $request->name;
        $store->description = $request->description;
        $store->seller_id = auth()->user()->id;
        $store->save();

        $products = Product::where('seller_id', auth()->id())->get();
        $store_items = [];
        foreach($products as $p) {
            $store_items[] = [
                'product_id' => $p->product_id,
                'image' => $p->image,
                'price' => $p->price,
                'quantity' => $p->quantity,
                'name' => $p->name
            ];

            $store->product_items()->createMany($store_items);
        }
        // $data['seller_id'] = auth()->user()->id;

        // Store::create($data);
        return response()->json([
            'message'=>'Created',
            'data'=>$data
        ]);
    }


    /**
     * Delete store
     * @OA\Delete (
     *     path="/api/delete/store/{id}",
     *     tags={"Store"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile Deleted!")
     *         )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized!")
     *         )
     *     )
     * )
     */
    public function delete_store($id){
        $seller_id = auth()->user()->id;
        $seller = Seller::findOrFail($seller_id);
        if ($seller->user_id != auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
        $store = Store::findOrFail($id);
        Store::destroy($store);
        return response()->json([
            'msg'=>'Deleted!'
        ]);
    }
}
