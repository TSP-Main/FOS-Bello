<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TemporaryOrderController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('restaurant_detail', [APIController::class, 'restaurant_detail']);
Route::get('categories', [APIController::class, 'categories']);
Route::get('menu', [APIController::class, 'menu']);
Route::get('products/{id?}', [APIController::class, 'products']);
Route::get('schedule', [APIController::class, 'schedule']);
Route::get('categories_a', [APIController::class, 'categories_a']);
Route::get('category/products/{slug?}', [APIController::class, 'category_products']);
Route::get('options/detail', [APIController::class, 'get_option_value_detail']);
// Route::post('orders/process', [APIController::class, 'order_process']);

// store incoming order
Route::post('order/store', [OrderController::class, 'store_incoming_order']);

Route::get('/notifications', [NotificationController::class, 'index']);

Route::get('stripe/config', [APIController::class, 'stripe_config']);

Route::post('newsletter/subscribe', [APIController::class, 'newsletter_subscribe']);

// check discount code
Route::post('discount/check', [APIController::class, 'discount_check']);

Route::get('searching', [APIController::class, 'products_search']);

Route::get('customers', [APIController::class, 'customers']);

Route::post('stripe/webhook', [StripeController::class, 'handleWebhook']);

