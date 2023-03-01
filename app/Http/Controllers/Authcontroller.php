<?php

namespace App\Http\Controllers;

use App\Models\User;
use OpenApi\Generator;
use App\Forms\TokenForm as Form;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Authcontroller extends Controller
{

    /**
     * Create account
     * @OA\Post (
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Post your email and password and we will return a token. Use the token in the 'Authorization' header like so 'Bearer YOUR_TOKEN'",
     *     operationId="",
     *     description="",
     *     @OA\RequestBody(
     *       required=true,
     *       description="The Token Request"),
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
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password_confirmation",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="is_admin",
     *                          type="boolean"
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"example name",
     *                     "email":"example email",
     *                     "password":"example password",
     *                     "password_confirmation":"example password_confirmation"
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
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="token", type="string", example="token"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="The provided credentials are incorrect."
     *       )
     * )
     */
    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'is_admin' => 'required|in:0,1'
        ]);

        $user = User::create([
            'name'=> $data['name'],
            'email'=> $data['email'],
            'password'=> bcrypt($data['password']),
            'is_admin' =>$data['is_admin']
        ]);

        if($user->is_admin == 1){
            $token = $user->createToken('Admin_token', ['server:admin'])->plainTextToken;
        }
        else{
            $token = $user->createToken('myapptoken')->plainTextToken;
        }

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }



    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logout successfully'
        ];
    }



    /**
     * sign in
     * @OA\Post (
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Post your email and password and we will return a token. Use the token in the 'Authorization' header like so 'Bearer YOUR_TOKEN'",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="is_admin",
     *                          type="integer"
     *                      ),
     *                 ),
     *                 example={
     *                     "email":"example email",
     *                     "password":"example password",
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
     *              @OA\Property(property="email", type="string", example="email"),
     *              @OA\Property(property="token", type="string", example="token"),
     *              @OA\Property(property="role", type="string", example="role"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="The provided credentials are incorrect."
     *       )
     * )
     */
    public function login(Request $request) {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        //Confirm email

        $user = User::where('email', $data['email'])->first();

        //Confirm Password

        if(!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Invalid email or password'
            ],401);
        } else{
            if($user->is_admin == 1){
                $role  = 'admin';
                $token = $user->createToken('Admin_token', ['server:admin'])->plainTextToken;
            }
            else{
                $role = '';
                $token = $user->createToken('myapptoken')->plainTextToken;
            }
        }




        $response = [
            'user' => $user,
            'token' => $token,
            'role'=>$role
        ];

        return response($response,201);

    }
}




// $customerRole = Role::create(['name' => 'customer']);
// $adminRole = Role::create(['name' => 'admin']);
