<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Create Customer profile
     * @OA\Post (
     *     path="/api/profile_create",
     *     tags={"Customer"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="first_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="last_name",
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
     *                          property="county",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "first_name":"example first_name",
     *                     "first_name":"example last_name",
     *                     "phone_no": "example phone_no",
     *                     "email":"example email",
     *                     "nearest_town":"example nearest_town",
     *                     "county":"example county",
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
     *              @OA\Property(property="first_name", type="string", example="first_name"),
     *              @OA\Property(property="last_name", type="string", example="last_name"),
     *              @OA\Property(property="phone_no", type="string", example="phone_no"),
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="nearest_town", type="string", example="nearest_town"),
     *              @OA\Property(property="county", type="string", example="county"),
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
    public function create_profile(Request $request){

        $user = Auth::user();
        if ($user->customer) {
            return response()->json(['error' => 'You already have a freelancer profile']);
        }
        $data = $request->validate([
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
        ]);
        $customer = new Customer;
        $customer->user_id = auth()->user()->id;
        $customer->first_name = $request->first_name;
        $customer->last_name= $request->last_name;
        $customer->balance = '40000';
        $customer->order = $request->order()->id;

        $customer->save();

        return response()->json([
            'message' => 'Profile created successfully!',
            'data' => $data
        ]);
    }


    /**
     * Update Customer profile
     * @OA\Put (
     *     path="/api/update_profile/{id}",
     *     tags={"Customer"},
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
     *                          property="first_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="last_name",
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
     *                          property="county",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="address",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "first_name":"example first_name",
     *                     "first_name":"example last_name",
     *                     "phone_no": "example phone_no",
     *                     "email":"example email",
     *                     "nearest_town":"example nearest_town",
     *                     "county":"example county",
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
     *              @OA\Property(property="first_name", type="string", example="first_name"),
     *              @OA\Property(property="last_name", type="string", example="last_name"),
     *              @OA\Property(property="phone_no", type="string", example="phone_no"),
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="nearest_town", type="string", example="nearest_town"),
     *              @OA\Property(property="county", type="string", example="county"),
     *              @OA\Property(property="address", type="string", example="address"),
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


    public function update_profile(Request $request, $id){
        $customer = Customer::findOrFail($id);
        if($customer->user_id != auth()->user()->id){
            return response()->json([
                'message'=>'Unauthorized!'
            ], 401);
        }
        $data = $request->validate([
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
        ]);

        $customer->update($data);

        return response()->json([
            'message' => 'Profile Updated successfully',
            'data'=>$data
        ]);
    }



    /**
     * Delete profile
     * @OA\Delete (
     *     path="/api/delete_profile/{id}",
     *     tags={"Customer"},
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

    public function delete_profile($id){
        $customer = Customer::findOrFail($id);
        if($customer->user_id != auth()->user()->id){
            return response()->json([
                'message'=>'Unauthorized!'
            ], 401);
        }
        $customer->delete();

        return response()->json([
            'message'=>'Profile Deleted!'
        ]);
    }



    /**
     * Get Profile details
     * @OA\Get (
     *     path="/api/profile/{id}",
     *     tags={"Customer"},
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
     *              @OA\Property(property="first_name", type="string", example="first_name"),
     *              @OA\Property(property="last_name", type="string", example="last_name"),
     *              @OA\Property(property="phone_no", type="string", example="phone_no"),
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="nearest_town", type="string", example="nearest_town"),
     *              @OA\Property(property="county", type="string", example="county"),
     *              @OA\Property(property="address", type="string", example="address"),
     *              @OA\Property(property="balance", type="string", example="balance"),
     *              @OA\Property(property="orders", type="string", example="orders"),
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



    public function profile($id){
        $customer = Customer::findOrFail($id);
        return response()->json(['data' => $customer]);
    }
}
