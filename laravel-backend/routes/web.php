<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Customer\HomeController as CustomerHomeController;
use App\Http\Controllers\Vendor\HomeController as VendorHomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\CartController as FrontendCartController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Frontend\CheckoutController as FrontendCheckoutController;


use App\Http\Controllers\Frontend\StripeController;

//Route::post('/checkout/cod', [StripeController::class, 'cod'])->name('checkout.cod');
//Route::post('/checkout/card', [StripeController::class, 'chargeCard'])->name('checkout.card');
Route::get('/thank-you', [StripeController::class, 'thankYou'])->name('thank.you');

Route::post('/checkout/process', [StripeController::class, 'process'])->name('checkout.process');
//Route::get('/thankyou', [StripeController::class, 'thankYou'])->name('thank.you');

Route::get('/checkout', [FrontendCheckoutController::class, 'index'])->name('checkout.index');
//Route::post('/checkout', [FrontendCheckoutController::class, 'store'])->name('checkout.store');
//Route::get('/order/thankyou/{id}', [StripeController::class, 'thankyou'])->name('order.thankyou');



// Page with search input
Route::get('/search', [FrontendProductController::class, 'searchPage'])->name('products.searchPage');

// AJAX route for live search
Route::get('/search/ajax', [FrontendProductController::class, 'ajaxSearch'])->name('products.ajaxSearch');



Route::get('/admin', function () {
   return view('login');
});

// Home page
Route::get('/', function () {
    return view('frontend.index');
});
Route::get('/category/{id}', [FrontendCategoryController::class, 'show'])->name('frontend.category.show');
Route::get('/products', [FrontendProductController::class, 'products'])->name('frontend.products');
// Product listing page
Route::get('/product/{alias}', [FrontendProductController::class, 'show'])->name('frontend.product.show');

Route::get('/cart', [FrontendCartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [FrontendCartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [FrontendCartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [FrontendCartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [FrontendCartController::class, 'clear'])->name('cart.clear');



Route::get('/', [FrontendCategoryController::class, 'index'])->name('home');
Route::get('/admin/dashboard', [AdminHomeController::class, 'index'])
    ->middleware('App\Http\Middleware\RoleMiddleware:admin')
    ->name('admin.dashboard');
Route::get('/vendor/dashboard', [VendorHomeController::class, 'index'])
    ->middleware('App\Http\Middleware\RoleMiddleware:vendor')
    ->name('vendor.dashboard');
Route::get('/customer/dashboard', [CustomerHomeController::class, 'index'])
    ->middleware('App\Http\Middleware\RoleMiddleware:customer')
    ->name('customer.dashboard');


    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
//route for create category
Route::middleware(['auth','App\Http\Middleware\RoleMiddleware:admin'])->prefix('admin')->group(function () {
    Route::resource('category', CategoryController::class);
});
//users
Route::middleware(['auth','App\Http\Middleware\RoleMiddleware:admin'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class);
     // Extra routes
  Route::put('users/{id}/change-role', [App\Http\Controllers\Admin\UserController::class, 'changeRole'])
     ->name('users.changeRole');
     Route::put('admin/users/{id}/change-password', [App\Http\Controllers\Admin\UserController::class, 'changePassword'])
     ->name('users.changePassword');

});
//products
Route::middleware(['auth','App\Http\Middleware\RoleMiddleware:admin'])->prefix('admin')->group(function () {
    Route::resource('product', ProductController::class);
    // Set primary image
Route::put('products/image/{id}/primary', [ProductController::class, 'setPrimaryImage'])
    ->name('product.setPrimaryImage');

// Delete an image
Route::delete('products/image/{id}', [ProductController::class, 'deleteImage'])
    ->name('product.deleteImage');
});

//Route::get('admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
//Route::post('admin/categories/store', [CategoryController::class, 'store'])->name('categories.store');


Route::get('contactus',[ContactController::class,'contact']);
Route::get('welcome',[ContactController::class,'welcome'])->name('welcome');

Route::get('register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::get('login', [RegisterController::class, 'showLoginForm'])->name('login');
Route::post('login', [RegisterController::class, 'login'])->name('login');

Route::post('logout', [RegisterController::class, 'logout'])->name('logout');


