<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RestaurantScheduleController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;


Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::group(['middleware' => ['auth']], function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboad');
    Route::get('dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');

    // Users Routes
    Route::get('users', [UserController::class, 'index'])->name('users.list');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/update', [UserController::class, 'update'])->name('users.update');
    // Route::delete('users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Company Routes
    Route::get('companies', [CompanyController::class, 'index'])->name('companies.list');
    Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('companies/store', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('companies/edit/{id}', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::post('companies/update', [CompanyController::class, 'update'])->name('companies.update');
    // Route::delete('companies/destroy/{id}'0, [CompanyController::class, 'destroy'])->name('companies.destroy');
    Route::post('companies/{id}/refresh-token', [CompanyController::class, 'refreshToken'])->name('companies.refreshToken');

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

    Route::get('/productsByCategory', [ProductController::class, 'productsByCategory'])->name('products.by.category');

    Route::post('/orders/approve/{id}', [AdminOrderController::class, 'approve'])->name('orders.approve');
    Route::get('/orders/reject/{id}', [AdminOrderController::class, 'reject'])->name('orders.reject');
    Route::get('/admin/new-orders', [AdminOrderController::class, 'index'])->name('orders.noti');

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/clear', [NotificationController::class, 'clear'])->name('notifications.clear');
    Route::post('/notifications/{id}/delete', [NotificationController::class, 'delete']);

    // Delivery Radius
    Route::get('radius', [RestaurantScheduleController::class, 'create_radius'])->name('radius.create');
    Route::post('radius/store', [RestaurantScheduleController::class, 'store_radius'])->name('radius.store');

});

   



   

