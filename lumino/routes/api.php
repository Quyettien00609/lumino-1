<?php

use App\Http\Controllers\Api\Cart\CartController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::middleware(['auth:sanctum'])->get('/user', [AuthenticatedSessionController::class,'user']);

Route::post('/register',[RegisteredUserController::class,'store']);
Route::post('/login',[AuthenticatedSessionController::class,'store']);
Route::post('/logout',[AuthenticatedSessionController::class,'destroy']);

//PRODUCT
Route::get('products/featured',[ProductController::class,'showActiveAndFeaturedProducts']);
Route::get('products/new',[ProductController::class,'showActiveAndNewProducts']);
Route::get('products/detail/{id}', [ProductController::class, 'show']);
Route::get('products/all', [ProductController::class, 'index']);
Route::middleware(['auth:sanctum', 'permission:product'])->group(function () {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/update/{id}', [ProductController::class, 'update']);
    Route::delete('products/delete/{id}', [ProductController::class, 'destroy']);

    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/update', [CartController::class, 'updateCart']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'removeFromCart']);
    Route::post('/cart/clear', [CartController::class, 'clearCart']);
    Route::get('/cart/sum', [CartController::class, 'SumCart']);

});

Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('categories/create', [CategoryController::class, 'store'])->name('categories.store');
Route::get('categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::put('categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::get('/categories/{id}/products', [CategoryController::class, 'getProductsByCategory']);


