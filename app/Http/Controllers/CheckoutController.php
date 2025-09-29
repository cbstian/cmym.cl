<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        $sessionId = session()->getId();

        Log::info('Checkout page accessed', [
            'session_id' => $sessionId,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Verificar si hay items en el carrito
        $sessionUserId = session('cart_user_id');
        $cartItems = [];

        if ($sessionUserId) {
            $sessionKey = 'cart_'.crc32($sessionUserId);
            $cartItems = session($sessionKey, []);

            Log::debug('Cart items loaded for checkout', [
                'session_id' => $sessionId,
                'cart_user_id' => $sessionUserId,
                'session_key' => $sessionKey,
                'items_count' => count($cartItems),
            ]);
        } else {
            Log::debug('No cart session user ID found', [
                'session_id' => $sessionId,
            ]);
        }

        // Si el carrito está vacío, redirigir al carrito con mensaje
        if (empty($cartItems)) {
            Log::warning('Checkout accessed with empty cart', [
                'session_id' => $sessionId,
                'cart_user_id' => $sessionUserId,
            ]);

            return redirect()->route('cart')->with('error', 'Tu carrito está vacío. Agrega productos antes de proceder al checkout.');
        }

        Log::info('Checkout page rendered successfully', [
            'session_id' => $sessionId,
            'items_count' => count($cartItems),
        ]);

        return view('checkout');
    }
}
