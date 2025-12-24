<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\AdminOrderController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (JWT PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (JWT + ROLE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'admin'])->group(function () {

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    

    // Product images
    Route::delete('/product-images/{id}', [ProductController::class, 'deleteImage']);
    Route::put('/product-images/{id}/set-primary', [ProductController::class, 'setPrimaryImage']);


    Route::get('/admin/orders', [AdminOrderController::class, 'index']);
    Route::put('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);

    
});
Route::delete('products/{product}/reviews/{review}', [ProductController::class, 'destroyRating']);




/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
  

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/clientproducts', [ProductController::class, 'clientsindex']);
Route::get('/search-products', [ProductController::class, 'clientsindex']);

/*
|--------------------------------------------------------------------------
| CHECKOUT & PAYMENT
|--------------------------------------------------------------------------
*/
Route::post('/checkout', [CheckoutController::class, 'store']);
Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent']);

Route::post('/products/{id}/rate', [ProductController::class, 'rateProduct']);

