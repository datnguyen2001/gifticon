<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\PaymentController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\api\TrademarkController;
use \App\Http\Controllers\api\ProductsController;
use \App\Http\Controllers\api\VoucherController;

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
Route::post('check-phone', [AuthController::class, 'checkPhone'])->name('login.check-phone');
Route::post('register/send-otp', [AuthController::class, 'registerSendOTP'])->name('register.send-otp');
Route::post('register/verify-otp', [AuthController::class, 'registerVerifyOTP'])->name('register.verify-otp');
Route::post('register/create-password', [AuthController::class, 'registerCreatePassword'])->name('register.create-password');
Route::post('register/create-profile', [AuthController::class, 'registerCreateProfile'])->name('register.create-profile');
Route::post('login/submit', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::post('password/verify-otp', [AuthController::class, 'passwordVerifyOTP']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

Route::get('banner', [HomeController::class, 'banner']);
Route::get('sale-today', [HomeController::class, 'saleToday']);
Route::get('trademark', [HomeController::class, 'trademark']);
Route::get('category', [HomeController::class, 'category']);
Route::get('may-like', [HomeController::class, 'mayLike']);
Route::get('search', [HomeController::class, 'search']);
Route::get('filter-trademark/{id}', [TrademarkController::class, 'filterTrademark']);
Route::get('detail-trademark/{id}', [TrademarkController::class, 'detailTrademark']);
Route::get('detail-product/{id}', [ProductsController::class, 'detailProduct']);

Route::middleware('check-jwt-auth')->group(function () {
    Route::get('my-voucher', [VoucherController::class, 'myVoucher']);
    Route::get('voucher-given', [VoucherController::class, 'voucherGiven']);
    Route::get('voucher-info/{id}', [VoucherController::class, 'voucherInfo']);
    Route::get('detail-voucher/{id}', [VoucherController::class, 'detailVoucher']);

    Route::get('cart', [CartController::class, 'index']);
    Route::get('cart-detail/{id}', [CartController::class, 'detail']);
    Route::post('delete-cart/{id}', [CartController::class, 'deleteCart']);
    Route::post('add-to-cart-submit', [CartController::class, 'addToCartSubmit']);

    Route::get('payment', [PaymentController::class, 'index']);
    Route::post('create-order', [PaymentController::class, 'createOrder']);
    Route::post('confirm-order', [PaymentController::class, 'confirmOrder']);

    Route::get('notification', [HomeController::class, 'notification']);
    Route::get('read-notification/{id}', [HomeController::class, 'readNotification']);

    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::post('logout', [AuthController::class, 'logout']);
});
