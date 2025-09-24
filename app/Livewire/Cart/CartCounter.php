<?php

namespace App\Livewire\Cart;

use Livewire\Attributes\On;
use Livewire\Component;

class CartCounter extends Component
{
    public int $itemCount = 0;

    public function mount(): void
    {
        $this->loadCartCount();
    }

    #[On('cart-updated')]
    public function loadCartCount(): void
    {
        try {
            // Obtener el ID de sesión único
            $sessionUserId = session('cart_user_id');

            if (! $sessionUserId) {
                $this->itemCount = 0;

                return;
            }

            // Obtener todos los items del carrito de la sesión usando la clave interna
            $sessionKey = 'cart_'.crc32($sessionUserId);
            $cartItems = session($sessionKey, []);

            // Calcular el total de items
            $this->itemCount = collect($cartItems)->sum('quantity');
        } catch (\Exception $e) {
            $this->itemCount = 0;
        }
    }

    public function render()
    {
        return view('livewire.cart.cart-counter');
    }
}
