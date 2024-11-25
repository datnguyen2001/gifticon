<?php

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\BrandController;
use App\Http\Controllers\web\CreateOrderController;
use App\Http\Controllers\web\DiscountController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\web\HomeController;
use \App\Http\Controllers\web\LoginController;
use \App\Http\Controllers\web\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('auth/{provider}/redirect', [LoginController::class , 'redirect']);
Route::get('auth/google/callback', [LoginController::class , 'googleCallback']);
Route::get('auth/facebook/callback', [LoginController::class , 'facebookCallback']);
Route::get('auth/zalo/callback', [LoginController::class , 'zaloCallback']);
Route::get('/dang-nhap', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::get('/dang-ky', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerSubmit'])->name('register.submit');
Route::get('/xac-thuc-ma-otp', [AuthController::class, 'showOtpVerify'])->name('otp.verify');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.submit');

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('thuong-hieu-yeu-thich/{slug}', [ShopController::class, 'trademark'])->name('trademark');
Route::get('/thuong-hieu/{slug}', [BrandController::class, 'detail'])->name('brand.detail');
Route::get('khuyen-mai-hom-nay/{slug}', [ShopController::class, 'promotionToday'])->name('promotion-today');
Route::get('co-the-ban-thich/{slug}', [ShopController::class, 'youLike'])->name('you-like');
Route::get('ho-tro-khach-hang/{slug}', [HomeController::class, 'customerSupport'])->name('customer-support');
Route::get('ve-chung-toi/{slug}', [HomeController::class, 'customerSupport'])->name('about-us');
Route::get('/chi-tiet/{slug}', [ProductController::class, 'detail'])->name('product.detail');
Route::get('thanh-toan', [HomeController::class, 'order'])->name('order');
Route::get('gio-hang', [HomeController::class, 'cart'])->name('cart');
Route::post('/toggle-favorite/{id}', [HomeController::class, 'toggleFavorite']);
Route::get('search', [HomeController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/tao-don-hang', [CreateOrderController::class, 'index'])->name('create-order.index');
    Route::get('/thong-tin-ca-nhan', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/thong-tin-ca-nhan', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/cap-nhat-mat-khau', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout.submit');
    Route::post('/send-otp-profile', [ProfileController::class, 'sendOTP'])->name('profile.sendOTP');
    Route::post('/verify-otp-profile', [ProfileController::class, 'verifyOTPProfile'])->name('profile.verifyOTP');

    Route::get('phieu/{slug}', [HomeController::class, 'myVote'])->name('my-vote');
    Route::get('chi-tiet-phieu-cua-toi', [HomeController::class, 'detailMyVote'])->name('detailmy-vote');
    Route::get('voucher', [HomeController::class, 'voucher'])->name('voucher');
});
