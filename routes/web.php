<?php

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\BarcodeController;
use App\Http\Controllers\web\BrandController;
use App\Http\Controllers\web\CartController;
use App\Http\Controllers\web\CreateOrderController;
use App\Http\Controllers\web\DiscountController;
use App\Http\Controllers\web\MyVoteController;
use App\Http\Controllers\web\PaymentController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\web\HomeController;
use \App\Http\Controllers\web\LoginController;
use \App\Http\Controllers\web\ShopController;
use App\Http\Controllers\TwilioController;

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
Route::get('/kiem-tra-so-dien-thoai', [AuthController::class, 'checkPhone'])->name('check-phone');
Route::post('/verify-otp-phone', [AuthController::class, 'verifyOtpPhone'])->name('verify-phone-otp');
Route::get('/xac-thuc-ma-otp-so-dien-thoai/{phone}', [AuthController::class, 'showOtpVerifyPhone'])->name('phone.otp.verify');
Route::post('/verify-otp-password', [AuthController::class, 'verifyOtpPassword'])->name('password.otp.submit');

Route::get('/quen-mat-khau/{phone}', [AuthController::class, 'forgetPassword'])->name('forget-password');
Route::post('/save-forget-password', [AuthController::class, 'saveForgetPassword'])->name('save.forget.password');

//Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/', [HomeController::class, 'homeNew'])->name('home');
Route::get('thuong-hieu-yeu-thich/{slug}', [ShopController::class, 'trademark'])->name('trademark');
Route::get('/thuong-hieu/{slug}', [BrandController::class, 'detail'])->name('brand.detail');
Route::get('khuyen-mai-hom-nay/{slug}', [ShopController::class, 'promotionToday'])->name('promotion-today');
Route::get('co-the-ban-thich/{slug}', [ShopController::class, 'youLike'])->name('you-like');
Route::get('san-pham-moi/{slug}', [ShopController::class, 'productNew'])->name('product-new');
Route::get('ho-tro-khach-hang/{slug}', [HomeController::class, 'customerSupport'])->name('customer-support');
Route::get('ve-chung-toi/{slug}', [HomeController::class, 'customerSupport'])->name('about-us');
Route::get('/chi-tiet/{slug}', [ProductController::class, 'detail'])->name('product.detail');
Route::post('/toggle-favorite/{id}', [HomeController::class, 'toggleFavorite']);
Route::get('search', [HomeController::class, 'search'])->name('search');
Route::post('/product-reviews', [ProductController::class, 'storeReviews'])->name('product-reviews.store');
Route::get('/load-reviews', [ProductController::class, 'loadReviews'])->name('load.reviews');

Route::get('/kiem-tra-barcode', [BarcodeController::class, 'index'])->name('barcode.index');
Route::post('/kiem-tra-barcode', [BarcodeController::class, 'scanBarcode'])->name('barcode.scan');

Route::middleware('auth')->group(function () {
//    Route::get('/gio-hang/tao-don-hang', [CreateOrderController::class, 'indexCart'])->name('create-order.add-cart.index');
    Route::post('/them-gio-hang', [CreateOrderController::class, 'addToCartSubmit'])->name('create-order.add-cart.submit');
    Route::get('/mua-ngay/tao-don-hang', [CreateOrderController::class, 'indexBuyNow'])->name('create-order.buy-now.index');
    Route::post('/them-mua-ngay', [CreateOrderController::class, 'buyNowSubmit'])->name('create-order.buy-now.submit');
    Route::post('/xac-nhan-don-hang', [CreateOrderController::class, 'confirmOrder'])->name('create-order.confirm');
    Route::get('/download/example', [CreateOrderController::class, 'downloadExcel'])->name('download.example');

    Route::get('/thong-tin-ca-nhan', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/thong-tin-ca-nhan', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/cap-nhat-mat-khau', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout.submit');
    Route::post('/send-otp-profile', [ProfileController::class, 'sendOTP'])->name('profile.sendOTP');
    Route::post('/verify-otp-profile', [ProfileController::class, 'verifyOTPProfile'])->name('profile.verifyOTP');

    Route::get('phieu/{slug}', [MyVoteController::class, 'myVote'])->name('my-vote');
    Route::get('chi-tiet-phieu-cua-toi/{id}', [MyVoteController::class, 'detailMyVote'])->name('detailmy-vote');
    Route::get('voucher/{id}', [MyVoteController::class, 'voucher'])->name('voucher');

    Route::get('gio-hang', [CartController::class, 'index'])->name('cart.index');
    Route::post('xoa-gio-hang', [CartController::class, 'deleteCart'])->name('cart.delete');
    Route::post('gio-hang/thanh-toan', [CartController::class, 'payment'])->name('cart.payment');

    Route::get('thanh-toan', [PaymentController::class, 'index'])->name('order.index');
});

//Route::post('send-mms', [\App\Http\Controllers\SinchController::class, 'sendMms']);
Route::get('/zalo/callback', [\App\Http\Controllers\ZaloController::class, 'getAccessToken']);
Route::post('/send-zalo-message', [\App\Http\Controllers\ZaloController::class, 'sendMessage']);

