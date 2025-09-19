<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RequiredParameters;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\OrderController;

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

Route::prefix('api')->group(function () {
	
	Route::any('/get/banners',[GeneralController::class, 'getBanners']);
	Route::any('/get/brands',[GeneralController::class, 'getBrands']);
	Route::any('/get/cms',[GeneralController::class, 'getCms']);
	Route::any('/get/brands/by/category',[GeneralController::class, 'getBrandsByCategory']);
	Route::any('/get/capacity/by/category',[GeneralController::class, 'getCapacityByCategory']);

    #account setup
    Route::any('/customer/login',[CustomerController::class, 'login'])->name('api.login');
	Route::post('/customer/create',[CustomerController::class, 'createAccount'])->name('api.createAccount');
	Route::post('/customer/forgot-password',[CustomerController::class, 'forgotPassword'])->name('api.forgotPassword');
	Route::post('/customer/reset-password',[CustomerController::class, 'resetPassword'])->name('api.resetPassword');
	Route::any('/customer/logout',[CustomerController::class, 'logout'])->name('api.logout');
	
	Route::any('/check/login',[CustomerController::class, 'checkLogin'])->middleware(RequiredParameters::class);
	Route::any('/get/profile',[CustomerController::class, 'getProfile'])->middleware(RequiredParameters::class);
	Route::any('/update/profile',[CustomerController::class, 'updateProfile'])->middleware(RequiredParameters::class);
	Route::any('/update/password',[CustomerController::class, 'updatePassword'])->middleware(RequiredParameters::class); 
	
	#products
	Route::any('/get/feature-products',[ProductController::class, 'getFeatureProducts'])->name('api.get.feature.product');
	Route::any('/get/product',[ProductController::class, 'getProduct']);
	Route::any('/get/categories',[ProductController::class, 'getCategories']);
	Route::any('/get/category-products',[ProductController::class, 'getCategoryProducts']);
	Route::any('/get/brand-products',[ProductController::class, 'getBrandProducts']);
	Route::any('/get/sub-categories',[ProductController::class, 'getSubcategories']);
	Route::any('/get/wholesale-products',[ProductController::class, 'getWholeProducts']);
	Route::any('/add/to/wishlist',[ProductController::class, 'addToWishlist']);
	Route::any('/wishlist/list',[ProductController::class, 'wishlistList'])->middleware(RequiredParameters::class);
	Route::any('/wishlist/delete',[ProductController::class, 'wishlistDelete']);
	
	#cart
	Route::post('/set/cart',[CartController::class, 'setCart']);
	Route::post('/remove/cart',[CartController::class, 'removeCart']);
	Route::post('/get/cart/list',[CartController::class, 'getCartList'])->name('api.get.cart.list');
	Route::post('/cart/update',[CartController::class, 'updateCart']);
	Route::post('/cart/count',[CartController::class, 'countCart']);
	
	#general
	Route::post('/set/newsletter',[GeneralController::class, 'setNewsletter']);
	Route::post('/get/site-profile',[GeneralController::class, 'getProfile']);
	Route::post('/get/settings',[GeneralController::class, 'getSetting']);
	Route::post('/save/contact',[GeneralController::class, 'saveContact']);
	
	#order
	Route::post('/place/order',[OrderController::class, 'placeOrder']);
	Route::post('/get/order/list',[OrderController::class, 'getOrderList'])->middleware(RequiredParameters::class);
	Route::post('/get/order',[OrderController::class, 'getOrder']);
	Route::post('/apply/coupon',[OrderController::class, 'applyCoupon']);
	Route::post('/remove/coupon',[OrderController::class, 'removeCoupon']);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
