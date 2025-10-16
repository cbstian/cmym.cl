<?php

use App\Livewire\Checkout;
use App\Models\Category;
use App\Models\Location\Commune;
use App\Models\Location\Region;
use App\Models\Product;
use App\Settings\EcommerceSettings;
use Livewire\Livewire;

beforeEach(function () {
    // Configurar settings de prueba
    $settings = app(EcommerceSettings::class);
    $settings->emails_notifications_orders = ['test@example.com'];
    $settings->bank_details = 'Test bank details';
    $settings->email_confirmation_payment = 'test@example.com';
    $settings->courier_companies = ['Starken', 'Chilexpress'];
    $settings->shipping_costs_rm = [];
    $settings->save();
});

it('calculates shipping cost for RM region based on commune', function () {
    // Crear región metropolitana y comunas
    $rmRegion = Region::factory()->create([
        'name' => 'Metropolitana de Santiago',
        'abbreviation' => 'RM',
    ]);

    $commune = Commune::factory()->create([
        'name' => 'Santiago',
        'province_id' => 1, // Asumiendo que existe una provincia
        'active' => true,
    ]);

    // Configurar costo de envío para esta comuna
    $settings = app(EcommerceSettings::class);
    $settings->shipping_costs_rm = [$commune->id => 10000];
    $settings->save();

    // Crear producto y agregarlo al carrito
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'price' => 50000,
        'stock_quantity' => 10,
    ]);

    // Simular carrito en sesión
    $sessionUserId = 'test-user-123';
    session(['cart_user_id' => $sessionUserId]);
    $sessionKey = 'cart_'.crc32($sessionUserId);
    session([
        $sessionKey => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
    ]);

    // Renderizar componente Checkout
    $component = Livewire::test(Checkout::class)
        ->set('shipping_region_id', $rmRegion->id)
        ->set('shipping_commune_id', $commune->id);

    // Verificar que el costo de envío es el configurado
    expect($component->shipping_cost)->toBe(10000);
    expect($component->isRegionRM)->toBeTrue();
    expect($component->shippingType)->toBe('fixed');
});

it('shows to_pay shipping for non-RM regions', function () {
    // Crear región no metropolitana
    $otherRegion = Region::factory()->create([
        'name' => 'Valparaíso',
        'abbreviation' => 'V',
    ]);

    // Crear producto y agregarlo al carrito
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'price' => 50000,
        'stock_quantity' => 10,
    ]);

    // Simular carrito en sesión
    $sessionUserId = 'test-user-123';
    session(['cart_user_id' => $sessionUserId]);
    $sessionKey = 'cart_'.crc32($sessionUserId);
    session([
        $sessionKey => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
    ]);

    // Renderizar componente Checkout
    $component = Livewire::test(Checkout::class)
        ->set('shipping_region_id', $otherRegion->id);

    // Verificar que el costo de envío es 0 (por pagar)
    expect($component->shipping_cost)->toBe(0);
    expect($component->isRegionRM)->toBeFalse();
    expect($component->shippingType)->toBe('to_pay');
});

it('requires courier company selection for non-RM regions', function () {
    // Crear región no metropolitana
    $otherRegion = Region::factory()->create([
        'name' => 'Valparaíso',
        'abbreviation' => 'V',
    ]);

    $commune = Commune::factory()->create([
        'name' => 'Valparaíso',
        'province_id' => 1,
        'active' => true,
    ]);

    // Crear producto y agregarlo al carrito
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'price' => 50000,
        'stock_quantity' => 10,
    ]);

    // Simular carrito en sesión
    $sessionUserId = 'test-user-123';
    session(['cart_user_id' => $sessionUserId]);
    $sessionKey = 'cart_'.crc32($sessionUserId);
    session([
        $sessionKey => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
    ]);

    // Renderizar componente Checkout sin seleccionar courier
    $component = Livewire::test(Checkout::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('phone', '+56912345678')
        ->set('shipping_region_id', $otherRegion->id)
        ->set('shipping_commune_id', $commune->id)
        ->set('shipping_address_line_1', 'Test Address')
        ->set('payment_method', 'webpay')
        ->call('processCheckout');

    // Debe fallar la validación por falta de courier_company
    $component->assertHasErrors('courier_company');
});

it('does not require courier company for RM region', function () {
    // Crear región metropolitana
    $rmRegion = Region::factory()->create([
        'name' => 'Metropolitana de Santiago',
        'abbreviation' => 'RM',
    ]);

    $commune = Commune::factory()->create([
        'name' => 'Santiago',
        'province_id' => 1,
        'active' => true,
    ]);

    // Crear producto y agregarlo al carrito
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'price' => 50000,
        'stock_quantity' => 10,
    ]);

    // Simular carrito en sesión
    $sessionUserId = 'test-user-123';
    session(['cart_user_id' => $sessionUserId]);
    $sessionKey = 'cart_'.crc32($sessionUserId);
    session([
        $sessionKey => [
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ],
    ]);

    // Renderizar componente Checkout sin courier_company
    $component = Livewire::test(Checkout::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('phone', '+56912345678')
        ->set('shipping_region_id', $rmRegion->id)
        ->set('shipping_commune_id', $commune->id)
        ->set('shipping_address_line_1', 'Test Address');

    // No debe tener error en courier_company
    $component->assertHasNoErrors('courier_company');
});
