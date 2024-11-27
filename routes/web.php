<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::resource('parts', App\Http\Controllers\PartController::class);

Route::resource('part-webpages', App\Http\Controllers\PartWebpageController::class);

Route::resource('customers', App\Http\Controllers\CustomerController::class);

Route::resource('customer-payment-methods', App\Http\Controllers\CustomerPaymentMethodController::class);

Route::get('shopping-carts/add-item', [App\Http\Controllers\ShoppingCartController::class, 'addItem']);
Route::get('shopping-carts/remove-item', [App\Http\Controllers\ShoppingCartController::class, 'removeItem']);
Route::get('shopping-carts/empty', [App\Http\Controllers\ShoppingCartController::class, 'empty']);
Route::get('shopping-carts/order', [App\Http\Controllers\ShoppingCartController::class, 'order']);

Route::resource('orders', App\Http\Controllers\OrderController::class)->only('index', 'store', 'show');
