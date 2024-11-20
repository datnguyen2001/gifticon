<?php

use App\Http\Controllers\shop\ProductController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\shop\LoginController;
use \App\Http\Controllers\shop\DashboardController;



Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/dologin', [LoginController::class, 'doLogin'])->name('doLogin');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('check-shop-auth')->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('index');

    Route::get('profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('update-profile', [DashboardController::class, 'updateProfile'])->name('update-profile');

    Route::resource('product', ProductController::class);
});
