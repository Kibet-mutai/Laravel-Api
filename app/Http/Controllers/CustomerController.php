<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
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


    public function profile($id){
        $customer = Customer::findOrFail($id);
        return response()->json(['data' => $customer]);
    }
}
