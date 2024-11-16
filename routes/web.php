<?php

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\BrandController;
use App\Http\Controllers\web\CreateOrderController;
use App\Http\Controllers\web\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\web\HomeController;
use \App\Http\Controllers\web\LoginController;

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
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerSubmit'])->name('register.submit');

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('thuong-hieu-yeu-thich', [HomeController::class, 'trademark'])->name('trademark');
Route::get('khuyen-mai-hom-nay', [HomeController::class, 'promotionToday'])->name('promotion-today');
Route::get('co-the-ban-thich', [HomeController::class, 'youLike'])->name('you-like');
Route::get('/thuong-hieu/{id}', [BrandController::class, 'detail'])->name('brand.detail');
Route::get('phieu-cua-toi', [HomeController::class, 'myVote'])->name('my-vote');

Route::middleware('auth')->group(function () {
    Route::get('/tao-don-hang', [CreateOrderController::class, 'index'])->name('create-order.index');
    Route::get('/thong-tin-ca-nhan', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/thong-tin-ca-nhan', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/cap-nhat-mat-khau', [ProfileController::class, 'updatePassword'])->name('password.update');
});
