<?php

namespace App\Http\Controllers;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        // Verificar si hay items en el carrito
        $sessionUserId = session('cart_user_id');
        $cartItems = [];

        if ($sessionUserId) {
            $sessionKey = 'cart_'.crc32($sessionUserId);
            $cartItems = session($sessionKey, []);
        }

        // Si el carrito está vacío, redirigir al carrito con mensaje
        if (empty($cartItems)) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío. Agrega productos antes de proceder al checkout.');
        }

        return view('checkout');
    }
}
