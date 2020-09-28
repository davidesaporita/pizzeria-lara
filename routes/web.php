<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Customer;
use App\Http\Controllers\Merchant;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

// Merchant
Route::middleware('merchant')->prefix('merchant')->name('merchant.')->group(function () {

    // Dashboard
    Route::get('/', [Merchant\MerchantController::class, 'dashboard'])->name('index');
    Route::resource('/products', Merchant\ProductsController::class);

    // Orders received
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/index', [Merchant\OrderController::class, 'index'])->name('index');
    });
});

// Customer
Route::middleware('customer')->prefix('customer')->name('customer.')->group(function () {

    // Dashboard
    Route::get('/', [Customer\CustomerController::class, 'dashboard'])->name('index');

    // Cart & Checkout
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::post('/add',       [Customer\CartController::class, 'add'])       ->name('add');
        Route::post('/empty',     [Customer\CartController::class, 'empty'])     ->name('empty');
        Route::post('/emptyRow',  [Customer\CartController::class, 'emptyRow'])  ->name('emptyRow');
        Route::post('/increment', [Customer\CartController::class, 'increment']) ->name('increment');
        Route::post('/decrement', [Customer\CartController::class, 'decrement']) ->name('decrement');
        Route::get('/pay',        [Customer\CartController::class, 'pay'])       ->name('pay');
        Route::post('/checkout',  [Customer\CartController::class, 'checkout'])  ->name('checkout');
    });
});

