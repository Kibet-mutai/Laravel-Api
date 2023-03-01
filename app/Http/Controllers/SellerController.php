<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{

    /**
     * Get Profile details
     * @OA\Get (
     *     path="/api/seller/{id}",
     *     tags={"Seller"},
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
     *              @OA\Property(property="full_name", type="string", example="full_name"),
     *              @OA\Property(property="phone_no", type="string", example="phone_no"),
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="nearest_town", type="string", example="nearest_town"),
     *              @OA\Property(property="address", type="string", example="address"),
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
    public function show($id){
        $seller = Seller::findOrFail($id);
        if ($seller->user_id != auth()->user()->id){
            return response()->json([
                'message'=>'Unauthorized'
            ], 401);
        }
        return response()->json([
            'data'=>$seller
        ]);

    }




    /**
     * Create Seller's Account
     * @OA\Post (
     *     path="/api/create",
     *     tags={"Seller"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="full_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone_no",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="nearest_town",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "first_name":"example first_name",
     *                     "phone_no": "example phone_no",
     *                     "email":"example email",
     *                     "nearest_town":"example nearest_town",
     *                     "address":"example address"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="full_name", type="string", example="full_name"),
     *              @OA\Property(property="phone_no", type="string", example="phone_no"),
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="nearest_town", type="string", example="nearest_town"),
     *              @OA\Property(property="address", type="string", example="address"),
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

     public function create(Request $request){
        $user = Auth::user();
        if ($user->seller) {
            return response()->json(['error' => 'You already have an account!']);
        }
        $data = $request->validate([
            'full_name'=>'required|string|max:255',
            // 'email'=>'required|email:unique',
            // 'phone_no'=>'required',
            'address'=>'required',
            'nearest_town' => 'required'
        ]);

        $seller = new Seller;
        $seller->user_id = auth()->user()->id;
        $seller->full_name = $request->full_name;
        // $seller->email = $request->email;
        // $seller->phone_no = $request->phone_no;
        $seller->address = $request->address;
        $seller->nearest_town = $request->nearest_town;

        $seller->save();

        return response()->json([
            'message'=>'Account created successfully',
            'data'=>$data
        ]);
     }

     /**
     * Update Account
     * @OA\Put (
     *     path="/api/update/{id}",
     *     tags={"Seller"},
     *       @OA\Parameter(
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
     *                          property="full_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone_no",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="nearest_town",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "first_name":"example first_name",
     *                     "phone_no": "example phone_no",
     *                     "email":"example email",
     *                     "nearest_town":"example nearest_town",
     *                     "address":"example address"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="full_name", type="string", example="full_name"),
     *              @OA\Property(property="phone_no", type="string", example="phone_no"),
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="nearest_town", type="string", example="nearest_town"),
     *              @OA\Property(property="address", type="string", example="address"),
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

     public function update(Request $request, $id){
        $seller = Seller::findOrFail($id);
        if($seller->user_id != auth()->user()->id){
            return response()->json([
                'message'=>'Unauthorized!'
            ], 401);
        }

        $data = $request->validate([
            'full_name'=>'required|string|max:255',
            'email'=>'required|email:unique',
            'phone_no'=>'required',
            'address'=>'required',
            'nearest_town' => 'required'
        ]);
        $seller->update($data);

        return response()->json([
            'message' => 'Profile Updated successfully',
            'data'=>$data
        ]);
     }



     /**
     * Delete profile
     * @OA\Delete (
     *     path="/api/delete/{id}",
     *     tags={"Seller"},
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

     public function delete($id){
        $seller = Seller::findOrFail($id);
        if($seller->user_id != auth()->user()->id){
            return response()->json([
                'message'=>'Unauthorized!'
            ], 401);
        }
        $seller->delete();

        return response()->json([
            'message'=>'Profile Deleted!'
        ]);
    }

}
