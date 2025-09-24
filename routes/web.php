<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/productos', [ProductController::class, 'index'])->name('products');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/carrito', function () {
    return view('cart');
})->name('cart');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

Route::get('/contacto', [HomeController::class, 'contact'])->name('contact');
Route::get('/quienes-somos', [HomeController::class, 'about'])->name('about');
