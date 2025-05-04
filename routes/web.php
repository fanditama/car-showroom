<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Livewire\Car\CarDetail;
use App\Livewire\Profile\Settings;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Models\Car;
use App\Models\Transaction;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Socialite Routes
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// Account Settings Routes
Route::get('/account/settings', [AccountController::class, 'settings'])->name('account.settings');
Route::put('/account/profile/update', [AccountController::class, 'updateProfile'])->name('account.profile.update');
Route::put('/account/password/update', [AccountController::class, 'updatePassword'])->name('account.password.update');

Route::get('/cars/{car}', function (Car $car) {
    return view('car.car-detail-page', ['car' => $car]);
})->name('cars.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard/home', function () {
        return view('dashboard.home-page');
    })->name('dashboard.home');
    Route::get('/dashboard/user', function () {
        return view('dashboard.user-management-page');
    })->name('dashboard.user');
    Route::get('/dashboard/car', function () {
        return view('dashboard.car-inventory-page');
    })->name('dashboard.car');
    Route::get('/dashboard/transaction', function () {
        return view('dashboard.transaction-list-page');
    })->name('dashboard.transaction');
});

Route::middleware(['auth', 'user'])->prefix('user')->group(function () {
    Route::get('/cart', function() {
       return view('order.cart-index-page');
    })->name('cart.index');

    Route::get('/order/{car}', function(Car $car) {
        return view('order.order-form-page', ['car' => $car]);
    })->name('order.form');

    Route::get('/transaction-cart', function() {
        return view('transaction.transaction-cart-page');
    })->name('transaction.cart');

    Route::get('/transactions/{transaction}', function(App\Models\Transaction $transaction) {
        return view('transaction.transaction-detail-page', compact('transaction'));
    })->name('transactions.show');
});
