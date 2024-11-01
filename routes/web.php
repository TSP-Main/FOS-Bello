<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\NewsletterSubscription;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RestaurantScheduleController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Middleware\CheckPermission;

Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::post('signup', [CompanyController::class, 'register'])->name('register.self');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::group(['middleware' => ['auth']], function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');

    // Users Routes
    Route::get('users', [UserController::class, 'index'])->name('users.list');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/update', [UserController::class, 'update'])->name('users.update');
    // Route::delete('users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Company Routes
    Route::group(['middleware' => [CheckPermission::class . ':company']], function () {
        Route::get('companies', [CompanyController::class, 'index'])->name('companies.list');
        Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('companies/store', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('companies/edit/{id}', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::post('companies/update', [CompanyController::class, 'update'])->name('companies.update');
        // Route::delete('companies/destroy/{id}'0, [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::get('companies/incoming/list', [CompanyController::class, 'incoming_request'])->name('companies.incoming.list');
        Route::post('companies/incoming/action/{id}', [CompanyController::class, 'incoming_request_action'])->name('companies.incoming.action');
        Route::get('companies/revenue', [CompanyController::class, 'revenue'])->name('companies.revenue');
        Route::post('companies/generate/token', [CompanyController::class, 'generate_new_token'])->name('companies.generate.token');
        Route::get('companies/api/logs', [CompanyController::class, 'api_logs'])->name('companies.api.logs');
        Route::get('companies/view/{id}', [CompanyController::class, 'view'])->name('companies.view');
    });

    // Restaurant Schedule
    Route::get('schedules', [RestaurantScheduleController::class, 'index'])->name('schedules.list');
    Route::get('schedules/create', [RestaurantScheduleController::class, 'create'])->name('schedules.create');
    Route::post('schedules/store', [RestaurantScheduleController::class, 'store'])->name('schedules.store');

    // Products Routes
    Route::get('products', [ProductController::class, 'index'])->name('products.list');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('products/update/', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Product Options/Sides Routes
    Route::get('options', [OptionController::class, 'index'])->name('options.list');
    Route::get('options/create', [OptionController::class, 'create'])->name('options.create');
    Route::post('options/store', [OptionController::class, 'store'])->name('options.store');
    Route::get('options/edit/{id}', [OptionController::class, 'edit'])->name('options.edit');
    Route::post('options/update/', [OptionController::class, 'update'])->name('options.update');

    //Category Routes
    Route::get('/category', [CategoryController::class, 'index'])->name('category.list');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('category/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Menus Routes
    Route::get('menu', [MenuController::class, 'index'])->name('menu.list');
    Route::get('menu/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('menu/store', [MenuController::class, 'store'])->name('menu.store');
    Route::get('menu/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::post('menu/update/', [MenuController::class, 'update'])->name('menu.update');

    // Orders Routes
    Route::get('orders/list', [OrderController::class, 'index'])->name('orders.list');
    Route::get('orders/detail/{id}', [OrderController::class, 'detail'])->name('orders.detail');
    Route::get('send', [OrderController::class, 'send_mail']);
    Route::get('orders/incoming', [OrderController::class, 'check_incoming_orders'])->name('orders.incoming');
    Route::match(['get', 'post'], 'orders/update/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('orders/print/{id}', [OrderController::class, 'print'])->name('orders.print');
    Route::get('orders', [OrderController::class, 'ordersList'])->name('orders');
    Route::get('orders/filter', [OrderController::class, 'ordersFilter'])->name('orders.filter');
    Route::get('orders/walkin', [OrderController::class, 'walkin_order_form'])->name('orders.walkin');
    Route::post('orders/walkin/store', [OrderController::class, 'store_walkin_order'])->name('orders.walkin.store');

    Route::get('/productsByCategory', [ProductController::class, 'productsByCategory'])->name('products.by.category');

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/clear', [NotificationController::class, 'clear'])->name('notifications.clear');
    Route::post('/notifications/{id}/delete', [NotificationController::class, 'delete']);

    // Delivery Radius
    Route::get('radius', [RestaurantScheduleController::class, 'create_radius'])->name('radius.create');
    Route::post('radius/store', [RestaurantScheduleController::class, 'store_radius'])->name('radius.store');

    // TimeZone
    Route::get('timezone', [RestaurantScheduleController::class, 'create_timezone'])->name('timezone.create');
    Route::post('timezone/store', [RestaurantScheduleController::class, 'store_timezone'])->name('timezone.store');

    // Configurations
    Route::get('configurations', [RestaurantScheduleController::class, 'create_configurations'])->name('configurations.create');
    Route::post('email/store', [RestaurantScheduleController::class, 'email_store'])->name('email.store');
    Route::post('stripe/store', [RestaurantScheduleController::class, 'stripe_store'])->name('stripe.store');
    Route::post('shipping/store', [RestaurantScheduleController::class, 'free_shipping_store'])->name('free.shipping.store');
    Route::post('currency/store', [RestaurantScheduleController::class, 'currency_store'])->name('currency.store');
    Route::get('discount', [RestaurantScheduleController::class, 'discount'])->name('discount');
    Route::post('discount/store', [RestaurantScheduleController::class, 'discount_store'])->name('discount.store');
    
    // Newsletter
    Route::get('subscription/list', [NewsletterSubscriptionController::class, 'index'])->name('subscriptions.list');

    Route::get('product/options', [ProductController::class, 'getOptions'])->name('product.options');

});

Route::get('check_expiry', [CompanyController::class, 'check_expiry']);

Route::get('/pusher', function () {
    return view('pusher');
});

Route::get('renewal', [CompanyController::class, 'renewal'])->name('renewal');
Route::post('renewal/store', [CompanyController::class, 'renewal_store'])->name('renewal.store');

   



   

