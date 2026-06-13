<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return view('welcome');
});

Route::get('/menu', \App\Livewire\Menu::class)->name('menu');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('profile.show');
    })->name('dashboard');

    Route::get('/cart', \App\Livewire\Cart::class)->name('cart');
    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');

    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/order-success/{order}', function ($order) {
        return view('order-success', ['orderId' => $order]);
    })->name('order.success');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/menu-items', [MenuController::class, 'index'])->name('admin.menu');
    Route::post('/menu-items', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::put('/menu-items/{menuItem}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu-items/{menuItem}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');

    Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('admin.customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');

    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('admin.orders');
});
