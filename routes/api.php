<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductsController;

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
Route::get('/products', [ProductsController::class, 'index']);

Route::get('/products/search', [ProductsController::class, 'search_product']);
Route::get('/products/filter', [ProductsController::class, 'filter']);
Route::get('/products/{id}', [ProductsController::class, 'detail']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('admin/products', [ProductsController::class, 'store']);
    Route::put('admin/products/{id}', [ProductsController::class, 'update']);
    Route::delete('admin/products/{id}', [ProductsController::class, 'destroy']);
    Route::post('/logout', [Authcontroller::class, 'logout']);

    Route::get('/category', [CategoryController::class, 'index']);


    Route::post('admin/category', [CategoryController::class, 'store'])->middleware('admin');
    Route::put('admin/category/{id}', [CategoryController::class, 'update'])->middleware('admin');
    Route::delete('admin/category/{id}', [CategoryController::class, 'destroy'])->middleware('admin');
    Route::get('/category/{id}', [CategoryController::class, 'detail']);


    Route::post('/cart', [CartController::class, 'add_to_cart']);
    Route::put('update_cart/{id}', [CartController::class, 'update_cart']);
    Route::delete('delete_cart/{id}', [CartController::class, 'delete_cart']);
    Route::get('cart_items/{id}', [CartController::class, 'cart_detail']);


    Route::post('/order', [CheckoutController::class, 'place_order']);
    Route::post('/cancel/order/{id}', [CheckoutController::class, 'cancel_order']);
});


Route::get('/sales', [AdminController::class, 'sales']);
Route::get('/in_stock/{id}', [AdminController::class, 'in_stock']);
Route::get('/check/{id}', [AdminController::class, 'check']);
