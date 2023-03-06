<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\StoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [Authcontroller::class, 'register']);
Route::post('/login', [Authcontroller::class, 'login']);


Route::get('/products', [IndexController::class, 'index']);
Route::get('/home/search', [IndexController::class, 'search_product']);
Route::get('/home/filter', [IndexController::class, 'filter']);
Route::get('/products/{id}', [IndexController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/admin/products', [ProductsController::class, 'index']);
    Route::post('/admin/products/create', [ProductsController::class, 'store']);
    Route::put('/admin/products/update/{id}', [ProductsController::class, 'update']);
    Route::delete('/admin/products/delete/{id}', [ProductsController::class, 'destroy']);
    Route::get('/admin/products/{id}', [ProductsController::class, 'detail']);
    Route::post('/logout', [Authcontroller::class, 'logout']);

    Route::get('/category', [CategoryController::class, 'index']);


    Route::post('/admin/category', [CategoryController::class, 'store'])->middleware('admin');
    Route::put('/admin/category/{id}', [CategoryController::class, 'update'])->middleware('admin');
    Route::delete('/admin/category/{id}', [CategoryController::class, 'destroy'])->middleware('admin');
    Route::get('/category/{id}', [CategoryController::class, 'detail']);


    Route::post('/cart', [CartController::class, 'add_to_cart']);
    Route::put('update_cart/{id}', [CartController::class, 'update_cart']);
    Route::delete('delete_cart/{id}', [CartController::class, 'delete_cart']);
    Route::get('cart_items/{id}', [CartController::class, 'cart_detail']);


    Route::post('/order', [CheckoutController::class, 'place_order']);
    Route::post('/cancel/order/{id}', [CheckoutController::class, 'cancel_order']);

    Route::post('/profile_create', [CustomerController::class, 'create_profile']);
    Route::put('/update_profile/{id}', [CustomerController::class, 'update_profile']);
    Route::delete('/delete_profile/{id}', [CustomerController::class, 'delete_profile']);
    Route::get('profile/{id}', [CustomerController::class, 'profile']);

    Route::post('/create', [SellerController::class, 'create'])->middleware('admin');
    Route::put('/update/{id}', [SellerController::class, 'update'])->middleware('admin');
    Route::delete('/delete/{id}', [SellerController::class, 'delete'])->middleware('admin');
    Route::get('/seller/{id}', [SellerController::class, 'show'])->middleware('admin');

    Route::post('/create/store', [StoreController::class, 'create_store']);
    Route::delete('/delete/store/{id}', [StoreController::class, 'delete_store']);
    Route::get('/store/{id}', [StoreController::class, 'show']);
});


Route::get('/sales', [AdminController::class, 'sales']);
Route::get('/in_stock/{id}', [AdminController::class, 'in_stock']);
Route::get('/check/{id}', [AdminController::class, 'check']);
