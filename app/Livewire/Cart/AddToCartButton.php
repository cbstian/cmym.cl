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
        // Cargar atributos del producto si no están cargados
        $this->product = $product->load('attributes');

        // No pre-seleccionar atributos - el usuario debe elegir
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
        // Validar atributos requeridos
        foreach ($this->product->attributes as $attribute) {
            if ($attribute->is_required && empty($this->selectedAttributes[$attribute->id])) {
                session()->flash('error', 'Debes seleccionar un valor para: '.$attribute->name);

                return;
            }
        }

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

            // Preparar atributos con nombres para comparación
            $formattedAttributes = [];
            foreach ($this->selectedAttributes as $attributeId => $selectedValue) {
                $attribute = $this->product->attributes->find($attributeId);
                if ($attribute && ! empty($selectedValue)) {
                    $formattedAttributes[$attribute->name] = $selectedValue;
                }
            }

            // Buscar si el producto con los mismos atributos ya está en el carrito
            $existingItemIndex = null;
            foreach ($cartItems as $index => $item) {
                if (($item['itemable_id'] ?? null) === $this->product->id &&
                    ($item['itemable_type'] ?? null) === Product::class) {

                    // Comparar atributos
                    $existingAttributes = $item['attributes'] ?? [];
                    if ($this->attributesAreEqual($formattedAttributes, $existingAttributes)) {
                        $existingItemIndex = $index;
                        break;
                    }
                }
            }

            if ($existingItemIndex !== null) {
                // El producto con los mismos atributos ya existe, actualizar cantidad
                $currentQuantity = $cartItems[$existingItemIndex]['quantity'] ?? 0;
                $newQuantity = $currentQuantity + $this->quantity;

                // Validar que no exceda el stock
                if ($newQuantity > $this->product->stock_quantity) {
                    session()->flash('error', 'No puedes agregar más cantidad. Stock disponible: '.$this->product->stock_quantity);

                    return;
                }

                $cartItems[$existingItemIndex]['quantity'] = $newQuantity;
                session([$sessionKey => $cartItems]);

                session()->flash('success', 'Cantidad actualizada en el carrito ('.$newQuantity.' unidades)');
                $this->redirect(route('cart'));
            } else {
                // El producto no existe, agregarlo como nuevo item
                $item = [
                    'itemable' => $this->product,
                    'quantity' => $this->quantity,
                    'attributes' => $formattedAttributes,
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

    /**
     * Compara si dos arrays de atributos son iguales
     */
    private function attributesAreEqual(array $attributes1, array $attributes2): bool
    {
        // Si tienen diferentes cantidades de elementos, no son iguales
        if (count($attributes1) !== count($attributes2)) {
            return false;
        }

        // Comparar cada atributo
        foreach ($attributes1 as $key => $value) {
            if (! isset($attributes2[$key]) || $attributes2[$key] !== $value) {
                return false;
            }
        }

        return true;
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart-button');
    }
}
