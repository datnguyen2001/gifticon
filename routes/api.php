<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('register/send-otp', [AuthController::class, 'registerSendOTP'])->name('register.send-otp');
Route::post('register/verify-otp', [AuthController::class, 'registerVerifyOTP'])->name('register.verify-otp');
Route::post('register/create-password', [AuthController::class, 'registerCreatePassword'])->name('register.create-password');
Route::post('register/create-profile', [AuthController::class, 'registerCreateProfile'])->name('register.create-profile');
Route::post('login/check-phone', [AuthController::class, 'loginCheckPhone'])->name('login.check-phone');
Route::post('login/submit', [AuthController::class, 'loginSubmit'])->name('login.submit');

Route::get('banner', [HomeController::class, 'banner'])->name('home.banner');
Route::get('sale-today', [HomeController::class, 'saleToday'])->name('home.sale-today');
Route::get('recent-brand', [HomeController::class, 'recentBrand'])->name('home.recent-brand');
Route::get('category', [HomeController::class, 'category'])->name('home.category');
Route::get('may-like', [HomeController::class, 'mayLike'])->name('home.may-like');
Route::get('search', [HomeController::class, 'search'])->name('home.search');

Route::middleware('check-jwt-auth')->group(function () {

});
