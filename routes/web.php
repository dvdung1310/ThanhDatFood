<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, AuthController, OrderController, ContactController};
use App\Http\Controllers\Admin\{DashboardController, CategoryController, ProductController, MediaController, PostController, SettingController};

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');
Route::view('/chinh-sach', 'policy')->name('policy');
Route::get('/san-pham', [HomeController::class, 'products'])->name('products.index');
Route::get('/san-pham/{product:slug}', [HomeController::class, 'show'])->name('products.show');
Route::get('/san-pham/{product:slug}/mua-hang', [OrderController::class,'checkout'])->name('orders.checkout');
Route::post('/san-pham/{product:slug}/mua-hang', [OrderController::class,'store'])->name('orders.store');
Route::get('/don-hang/{order}/thanh-cong', [OrderController::class,'success'])->name('orders.success');
Route::get('/don-hang/{order}/payos-return', [OrderController::class,'returned'])->name('orders.return');
Route::get('/don-hang/{order}/payos-cancel', [OrderController::class,'cancel'])->name('orders.cancel');
Route::post('/payos/webhook', [OrderController::class,'webhook'])->name('payos.webhook');
Route::get('/tin-tuc-su-kien', [HomeController::class,'news'])->name('news.index');
Route::get('/tin-tuc-su-kien/{post:slug}', [HomeController::class,'post'])->name('news.show');
Route::view('/lien-he', 'contact')->name('contact');
Route::post('/lien-he', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::middleware('guest')->group(function(){ Route::get('/dang-nhap',[AuthController::class,'create'])->name('login'); Route::post('/dang-nhap',[AuthController::class,'store']); });
Route::post('/dang-xuat',[AuthController::class,'destroy'])->middleware('auth')->name('logout');
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function(){
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class)->except('show');
    Route::resource('posts', PostController::class)->except('show');
    Route::get('contact-messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::put('contact-messages/{contactMessage}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'update'])->name('contact-messages.update');
    Route::get('orders',[\App\Http\Controllers\Admin\OrderController::class,'index'])->name('orders.index');
    Route::get('orders/{order}',[\App\Http\Controllers\Admin\OrderController::class,'show'])->name('orders.show');
    Route::put('orders/{order}',[\App\Http\Controllers\Admin\OrderController::class,'update'])->name('orders.update');
    Route::get('settings',[SettingController::class,'edit'])->name('settings.edit');
    Route::put('settings',[SettingController::class,'update'])->name('settings.update');
    Route::get('payos',[\App\Http\Controllers\Admin\PayOSSettingController::class,'edit'])->name('payos.edit');
    Route::put('payos',[\App\Http\Controllers\Admin\PayOSSettingController::class,'update'])->name('payos.update');
    Route::post('settings/test-mail',[SettingController::class,'testMail'])->name('settings.test-mail');
    Route::post('media/upload', [MediaController::class,'upload'])->name('media.upload');
    Route::resource('media', MediaController::class)->only(['index','store','update','destroy'])->parameters(['media'=>'medium']);
});
