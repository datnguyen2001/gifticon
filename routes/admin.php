<?php

use App\Http\Controllers\admin\BehaviorController;
use App\Http\Controllers\admin\CommissionController;
use App\Http\Controllers\admin\MemberShipController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\admin\LoginController;
use \App\Http\Controllers\admin\DashboardController;
use \App\Http\Controllers\admin\SettingController;
use \App\Http\Controllers\admin\BannersController;
use \App\Http\Controllers\admin\CategoryController;
use \App\Http\Controllers\admin\FooterController;
use \App\Http\Controllers\admin\ShopController;
use \App\Http\Controllers\shop\OrderController;
use \App\Http\Controllers\admin\ProductController;


Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/dologin', [LoginController::class, 'doLogin'])->name('doLogin');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('check-admin-auth')->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('index');
    //Hiệu suất tổng thể
    Route::get('overall_performance', [DashboardController::class, 'overallPerformance'])->name('overall_performance');
    Route::get('get-revenue-data', [DashboardController::class, 'getRevenueData']);
    Route::get('statistics', [DashboardController::class, 'statistics']);
    Route::get('revenue-growth', [DashboardController::class, 'revenueGrowth']);

    Route::get('user_behavior', [BehaviorController::class, 'userBehavior'])->name('user_behavior');
    Route::get('user_behavior/{range}', [BehaviorController::class, 'userBehaviorRange'])->name('user_behavior_range');
    Route::get('user_behavior_shop/{range}', [BehaviorController::class, 'userBehaviorShopRange'])->name('user_behavior_shop_range');
    Route::get('user_behavior_percentage/{range}', [BehaviorController::class, 'userBehaviorPercentageRange'])->name('user_behavior_percentage_range');

    Route::get('commission', [CommissionController::class, 'commission'])->name('commission');
    Route::get('commission-range', [CommissionController::class, 'commissionRange'])->name('commission-range');

    //Hiệu suất từng gian hàng
    Route::get('performance_shop/{id}', [DashboardController::class, 'performanceShop'])->name('performance_shop');

    Route::prefix('banner')->name('banner.')->group(function () {
        Route::get('/', [BannersController::class, 'index'])->name('index');
        Route::get('create', [BannersController::class, 'create'])->name('create');
        Route::post('store', [BannersController::class, 'store'])->name('store');
        Route::get('delete/{id}', [BannersController::class, 'delete']);
        Route::get('edit/{id}', [BannersController::class, 'edit']);
        Route::post('update/{id}', [BannersController::class, 'update']);
    });

    Route::prefix('category')->name('category.')->group(function () {
        Route::get('', [CategoryController::class, 'index'])->name('index');
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::get('delete/{id}', [CategoryController::class, 'delete']);
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [CategoryController::class, 'update'])->name('update');
    });

    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('', [ShopController::class, 'index'])->name('index');
        Route::get('create', [ShopController::class, 'create'])->name('create');
        Route::post('store', [ShopController::class, 'store'])->name('store');
        Route::get('delete/{id}', [ShopController::class, 'delete']);
        Route::get('edit/{id}', [ShopController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [ShopController::class, 'update'])->name('update');
    });

    Route::resource('product', ProductController::class);

    Route::prefix('footer')->name('footer.')->group(function () {
        Route::get('/', [FooterController::class, 'index'])->name('index');
        Route::get('create', [FooterController::class, 'create'])->name('create');
        Route::post('store', [FooterController::class, 'store'])->name('store');
        Route::get('delete/{id}', [FooterController::class, 'delete']);
        Route::get('edit/{id}', [FooterController::class, 'edit']);
        Route::post('update/{id}', [FooterController::class, 'update']);
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('', [SettingController::class, 'index'])->name('index');
        Route::post('update', [SettingController::class, 'save'])->name('update');
    });

    Route::prefix('membership')->name('membership.')->group(function () {
        Route::get('', [MemberShipController::class, 'index'])->name('index');
        Route::post('update', [MemberShipController::class, 'save'])->name('update');
    });

    Route::prefix('order')->name('order.')->group(function (){
        Route::get('index/{status}', [OrderController::class,'getDataOrder'])->name('index');
        Route::get('detail/{id}', [OrderController::class,'orderDetail'])->name('detail');
        Route::get('status/{order_id}/{status_id}', [OrderController::class,'statusOrder'])->name('status');
    });

});

Route::post('ckeditor/upload', [DashboardController::class, 'upload'])->name('ckeditor.image-upload');
