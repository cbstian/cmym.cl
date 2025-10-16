<?php

namespace App\Services;

class CheckoutService
{
    /**
     * Marca el checkout como completado exitosamente
     * Limpia el carrito y los datos del checkout de la sesión
     */
    public static function markCheckoutComplete(): void
    {
        static::clearCart();
        static::clearCheckoutSession();
    }

    /**
     * Marca el checkout como fallido
     * Mantiene los datos en sesión para permitir reintento
     */
    public static function markCheckoutFailed(): void
    {
        // Los datos del checkout permanecen en sesión para permitir reintento
        // Solo registramos el estado para mostrar mensaje de error si es necesario
        session()->flash('checkout_payment_failed', true);
    }

    /**
     * Limpia el carrito de la sesión
     */
    private static function clearCart(): void
    {
        $sessionUserId = session('cart_user_id');

        if ($sessionUserId) {
            $sessionKey = 'cart_'.crc32($sessionUserId);
            session([$sessionKey => []]);
        }
    }

    /**
     * Limpia todos los datos del checkout de la sesión
     */
    private static function clearCheckoutSession(): void
    {
        // Obtener el nombre del componente para construir las claves de sesión
        $componentName = 'app.livewire.checkout';

        // Lista de propiedades que usan #[Session]
        $sessionProperties = [
            'name', 'email', 'phone', 'rut', 'company_name',
            'shipping_region_id', 'shipping_commune_id', 'shipping_address_line_1', 'shipping_address_line_2',
            'same_as_shipping', 'billing_region_id', 'billing_commune_id', 'billing_address_line_1', 'billing_address_line_2',
            'order_notes', 'payment_method', 'courier_company',
        ];

        // Limpiar cada propiedad de sesión
        foreach ($sessionProperties as $property) {
            $sessionKey = "livewire.{$componentName}.{$property}";
            session()->forget($sessionKey);
        }

        // También limpiar cualquier clave adicional que Livewire pueda usar
        $allSessionKeys = array_keys(session()->all());
        foreach ($allSessionKeys as $key) {
            if (str_starts_with($key, "livewire.{$componentName}.")) {
                session()->forget($key);
            }
        }
    }

    /**
     * Verifica si hay datos de checkout en sesión
     */
    public static function hasCheckoutData(): bool
    {
        return session()->has('livewire.app.livewire.checkout.email') ||
               session()->has('livewire.app.livewire.checkout.name');
    }
}
