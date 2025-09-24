<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/productos', [ProductController::class, 'index'])->name('products');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/carrito', function () {
    return view('cart');
})->name('cart');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

// Rutas de pagos
Route::prefix('payment')->name('payment.')->group(function () {
    // Webpay
    Route::match(['GET', 'POST'], '/webpay/init/{order}', [PaymentController::class, 'initWebpayPayment'])->name('webpay.init');
    Route::match(['GET', 'POST'], '/webpay/return', [PaymentController::class, 'handleWebpayReturn'])->name('webpay.return');

    // Páginas de resultado
    Route::get('/success/{payment}', [PaymentController::class, 'paymentSuccess'])->name('success');
    Route::get('/failed/{payment?}', [PaymentController::class, 'paymentFailed'])->name('failed');
    Route::get('/cancelled/{payment?}', [PaymentController::class, 'paymentCancelled'])->name('cancelled');
});

Route::get('/contacto', [HomeController::class, 'contact'])->name('contact');
Route::get('/quienes-somos', [HomeController::class, 'about'])->name('about');
