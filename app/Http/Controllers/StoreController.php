<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
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

        // $data['seller_id'] = auth()->user()->id;

        // Store::create($data);
        return response()->json([
            'message'=>'Created',
            'data'=>$data
        ]);
    }
}
