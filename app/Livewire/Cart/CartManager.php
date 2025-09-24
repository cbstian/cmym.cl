<?php

namespace App\Livewire\Cart;

use Livewire\Attributes\On;
use Livewire\Component;

class CartManager extends Component
{
    public $cartItems = [];

    public $total = 0;

    public function mount(): void
    {
        $this->loadCart();
    }

    #[On('cart-updated')]
    public function loadCart(): void
    {
        try {
            // Obtener el ID de sesión único
            $sessionUserId = session('cart_user_id');

            if (! $sessionUserId) {
                $this->cartItems = [];
                $this->total = 0;

                return;
            }

            // Obtener todos los items del carrito de la sesión (estructura de Laravel Cart)
            $sessionKey = 'cart_'.crc32($sessionUserId);
            $rawCartItems = session($sessionKey, []);

            // Transformar los datos para la vista
            $this->cartItems = collect($rawCartItems)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'product_id' => $item['itemable_id'] ?? null,
                    'product_name' => $item['product_name'] ?? 'Producto',
                    'product_sku' => $item['product_sku'] ?? null,
                    'product_price' => $item['product_price'] ?? 0,
                    'product_image' => $item['product_image'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'attributes' => $item['attributes'] ?? [],
                ];
            })->toArray();

            // Calcular el total
            $this->calculateTotal();
        } catch (\Exception $e) {
            $this->cartItems = [];
            $this->total = 0;
        }
    }

    public function updateQuantity(int $itemIndex, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($itemIndex);

            return;
        }

        try {
            $sessionUserId = session('cart_user_id');
            if (! $sessionUserId) {
                return;
            }

            $sessionKey = 'cart_'.crc32($sessionUserId);
            $cartItems = session($sessionKey, []);

            if (isset($cartItems[$itemIndex])) {
                $cartItems[$itemIndex]['quantity'] = $quantity;
                session([$sessionKey => $cartItems]);
                $this->loadCart();
                $this->dispatch('cart-updated');
                session()->flash('success', 'Cantidad actualizada');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar la cantidad');
        }
    }

    public function removeItem(int $itemIndex): void
    {
        try {
            $sessionUserId = session('cart_user_id');
            if (! $sessionUserId) {
                return;
            }

            $sessionKey = 'cart_'.crc32($sessionUserId);
            $cartItems = session($sessionKey, []);

            if (isset($cartItems[$itemIndex])) {
                unset($cartItems[$itemIndex]);
                // Reindexar el array
                $cartItems = array_values($cartItems);
                session([$sessionKey => $cartItems]);
                $this->loadCart();
                $this->dispatch('cart-updated');
                session()->flash('success', 'Producto eliminado del carrito');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el producto');
        }
    }

    public function clearCart(): void
    {
        try {
            $sessionUserId = session('cart_user_id');
            if (! $sessionUserId) {
                return;
            }

            $sessionKey = 'cart_'.crc32($sessionUserId);
            session([$sessionKey => []]);
            $this->loadCart();
            $this->dispatch('cart-updated');
            session()->flash('success', 'Carrito vaciado');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al vaciar el carrito');
        }
    }

    private function calculateTotal(): void
    {
        $this->total = collect($this->cartItems)->sum(function ($item) {
            return ($item['product_price'] ?? 0) * ($item['quantity'] ?? 0);
        });
    }

    public function getCartItemsCountProperty(): int
    {
        return collect($this->cartItems)->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart.cart-manager');
    }
}
