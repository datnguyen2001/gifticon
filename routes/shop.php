<?php

use App\Http\Controllers\shop\ProductController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\shop\LoginController;
use \App\Http\Controllers\shop\DashboardController;
use \App\Http\Controllers\shop\OrderController;



Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/dologin', [LoginController::class, 'doLogin'])->name('doLogin');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('check-shop-auth')->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('index');

    Route::get('revenue-orders', [DashboardController::class, 'revenueOrders'])->name('revenue-orders');
    Route::get('product-statistics', [DashboardController::class, 'productStatistics'])->name('product-statistics');

    Route::get('profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('update-profile', [DashboardController::class, 'updateProfile'])->name('update-profile');

    Route::resource('product', ProductController::class);

    Route::prefix('order')->name('order.')->group(function (){
        Route::get('index/{status}', [OrderController::class,'getDataOrderShop'])->name('index');
    });

});
