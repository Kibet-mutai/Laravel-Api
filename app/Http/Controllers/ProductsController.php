<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * Products home
     * @OA\Get (
     *     path="/api/admin/products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="price", type="string", example="price"),
     *              @OA\Property(property="quantity", type="string", example="quantity"),
     *              @OA\Property(property="category_id", type="string", example="category_id"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="image", type="string", example="image"),
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
    /**
     * Add Products
     * @OA\Put (
     *     path="/api/admin/products/create",
     *     tags={"Products"},
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
     *                          property="price",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="quantity",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="category_id",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="image",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"example name",
     *                     "name":"example price",
     *                     "quantity": "example quantity",
     *                     "category_id":"example category_id",
     *                     "description":"example description",
     *                     "image":"example image",
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
     *              @OA\Property(property="price", type="string", example="price"),
     *              @OA\Property(property="quantity", type="string", example="quantity"),
     *              @OA\Property(property="category_id", type="string", example="category_id"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="image", type="string", example="image"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated"),
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        if (auth()->check()) {
            $data = $request->validate([
                'name'=>'required',
                'price'=> 'required',
                'category_id' => 'required|exists:category,id',
                'description'=> 'required',
                'image'=> 'required',
                'quantity'=> 'required',
                // 'seller_id' => 'required|exists:seller_id'
            ]);

            if($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized!'
            ]);
        }
        $seller = auth()->user()->id;
        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->image = $request->image;
        $product->seller_id = $seller;
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

    /**
     * Get Products details
     * @OA\Get (
     *     path="/api/admin/products/{id}",
     *     tags={"Products"},
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
     *              @OA\Property(property="price", type="string", example="price"),
     *              @OA\Property(property="quantity", type="string", example="quantity"),
     *              @OA\Property(property="category_id", type="string", example="category_id"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="image", type="string", example="image"),
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

      /**
     * Update Products
     * @OA\Put (
     *     path="/api/admin/products/{id}",
     *     tags={"Products"},
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
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
     *                          property="price",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="quantity",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="category_id",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="image",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"example name",
     *                     "name":"example price",
     *                     "quantity": "example quantity",
     *                     "category_id":"example category_id",
     *                     "description":"example description",
     *                     "image":"example image",
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
     *              @OA\Property(property="price", type="string", example="price"),
     *              @OA\Property(property="quantity", type="string", example="quantity"),
     *              @OA\Property(property="category_id", type="string", example="category_id"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="image", type="string", example="image"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated"),
     *          )
     *      )
     * )
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

     /**
     * Delete products
     * @OA\Delete (
     *     path="/api/admin/products/delete/{id}",
     *     tags={"Products"},
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

