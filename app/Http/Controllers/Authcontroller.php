<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Authcontroller extends Controller
{
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
                $token = $user->createToken('Admin_token', ['server:admin'])->plainTextToken;
            }
            else{
                $token = $user->createToken('myapptoken')->plainTextToken;
            }
        }




        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);

    }
}




// $customerRole = Role::create(['name' => 'customer']);
// $adminRole = Role::create(['name' => 'admin']);
