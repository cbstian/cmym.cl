<?php

namespace App\Livewire\Cart;

use App\Models\Product;
use Binafy\LaravelCart\LaravelCart;
use Livewire\Component;

class AddToCartButton extends Component
{
    public Product $product;

    public int $quantity = 1;

    public bool $loading = false;

    public array $selectedAttributes = [];

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function increaseQuantity(): void
    {
        if ($this->quantity < 99 && $this->quantity < $this->product->stock_quantity) {
            $this->quantity++;
        }
    }

    public function decreaseQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updatedQuantity($value): void
    {
        $value = (int) $value;

        if ($value < 1) {
            $this->quantity = 1;
        } elseif ($value > 99) {
            $this->quantity = 99;
        } elseif ($value > $this->product->stock_quantity) {
            $this->quantity = $this->product->stock_quantity;
            session()->flash('error', 'Solo tenemos '.$this->product->stock_quantity.' unidades disponibles');
        } else {
            $this->quantity = $value;
        }
    }

    public function addToCart(): void
    {
        // Validar stock
        if ($this->product->stock_quantity < $this->quantity) {
            session()->flash('error', 'No hay suficiente stock disponible');

            return;
        }

        if ($this->product->stock_quantity <= 0) {
            session()->flash('error', 'Este producto está sin stock');

            return;
        }

        $this->loading = true;

        try {
            // Generar un ID único para la sesión si no existe
            $sessionUserId = session('cart_user_id', 'guest_'.uniqid());
            session(['cart_user_id' => $sessionUserId]);

            // Verificar si el producto ya existe en el carrito
            $sessionKey = 'cart_'.crc32($sessionUserId);
            $cartItems = session($sessionKey, []);

            // Buscar si el producto ya está en el carrito
            $existingItemIndex = null;
            foreach ($cartItems as $index => $item) {
                if (($item['itemable_id'] ?? null) === $this->product->id &&
                    ($item['itemable_type'] ?? null) === Product::class) {
                    $existingItemIndex = $index;
                    break;
                }
            }

            if ($existingItemIndex !== null) {
                // El producto ya existe, mostrar mensaje informativo y no hacer nada más
                $currentQuantity = $cartItems[$existingItemIndex]['quantity'] ?? 0;
                session()->flash('info', 'Este producto ya está en tu carrito ('.$currentQuantity.' unidades)');

                return;
            } else {
                // El producto no existe, agregarlo como nuevo item
                $item = [
                    'itemable' => $this->product,
                    'quantity' => $this->quantity,
                    'attributes' => $this->selectedAttributes,
                    'product_name' => $this->product->name,
                    'product_sku' => $this->product->sku,
                    'product_price' => $this->product->getPrice(),
                    'product_image' => $this->product->image_primary_path,
                ];

                // Usar el facade LaravelCart con driver de sesión
                LaravelCart::driver('session')->storeItem($item, crc32($sessionUserId));

                session()->flash('success', 'Producto agregado al carrito ('.$this->quantity.' unidades)');

                // Redirigir al carrito después de agregar exitosamente
                $this->redirect(route('cart'));
            }

            // Resetear formulario (solo si no hubo redirección)
            $this->quantity = 1;
            $this->selectedAttributes = [];

            // Disparar evento para actualizar contador del carrito
            $this->dispatch('cart-updated');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al agregar el producto al carrito: '.$e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart-button');
    }
}
