<?php

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\CreateOrderController;
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

Route::middleware('auth')->group(function () {
    Route::get('/tao-don-hang', [CreateOrderController::class, 'index'])->name('create-order.index');
});
