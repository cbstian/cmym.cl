<?php

use App\Livewire\Checkout;
use App\Models\Commune;
use App\Models\Product;
use App\Models\Region;
use Database\Seeders\RegionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ejecutar el seeder de regiones para todos los tests
    $this->seed(RegionSeeder::class);
});

it('can display checkout page', function () {
    $response = $this->get('/checkout');

    $response->assertStatus(200);
    $response->assertSeeLivewire(Checkout::class);
});

it('redirects to cart when cart is empty', function () {
    Livewire::test(Checkout::class)
        ->assertRedirect(route('cart'));
});

it('can process checkout with valid data', function () {
    // Crear datos de prueba
    $product = Product::factory()->create([
        'price' => 10000,
        'stock_quantity' => 5,
        'is_active' => true,
    ]);

    $region = Region::first();
    $commune = $region ? Commune::where('region_id', $region->id)->first() : null;

    if (! $region || ! $commune) {
        $this->markTestSkipped('No hay regiones/comunas disponibles para el test');
    }

    // Simular carrito con productos
    $sessionUserId = 'test_user_'.uniqid();
    $sessionKey = 'cart_'.crc32($sessionUserId);

    session([
        'cart_user_id' => $sessionUserId,
        $sessionKey => [
            [
                'itemable_id' => $product->id,
                'itemable_type' => Product::class,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'product_price' => $product->getPrice(),
                'product_image' => $product->image_primary_path,
                'quantity' => 2,
                'attributes' => [],
            ],
        ],
    ]);

    Livewire::test(Checkout::class)
        ->set('name', 'Juan Pérez')
        ->set('email', 'juan@test.com')
        ->set('phone', '+56912345678')
        ->set('shipping_region_id', $region->id)
        ->set('shipping_commune_id', $commune->id)
        ->set('shipping_address_line_1', 'Av. Principal 123')
        ->call('processCheckout')
        ->assertRedirect(route('home'))
        ->assertSessionHas('success');

    // Verificar que se creó el usuario
    $this->assertDatabaseHas('users', [
        'email' => 'juan@test.com',
        'name' => 'Juan Pérez',
    ]);

    // Verificar que se creó la orden
    $this->assertDatabaseHas('orders', [
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);
});

it('validates required fields', function () {
    // Simular carrito con productos
    $product = Product::factory()->create();
    $sessionUserId = 'test_user_'.uniqid();
    $sessionKey = 'cart_'.crc32($sessionUserId);

    session([
        'cart_user_id' => $sessionUserId,
        $sessionKey => [
            [
                'itemable_id' => $product->id,
                'itemable_type' => Product::class,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'product_price' => $product->getPrice(),
                'quantity' => 1,
            ],
        ],
    ]);

    Livewire::test(Checkout::class)
        ->call('processCheckout')
        ->assertHasErrors(['name', 'email', 'phone', 'shipping_region_id', 'shipping_commune_id', 'shipping_address_line_1']);
});

it('calculates totals correctly', function () {
    $product = Product::factory()->create(['price' => 10000]);
    $region = Region::first();

    if (! $region) {
        $this->markTestSkipped('No hay regiones disponibles para el test');
    }

    // Simular carrito
    $sessionUserId = 'test_user_'.uniqid();
    $sessionKey = 'cart_'.crc32($sessionUserId);

    session([
        'cart_user_id' => $sessionUserId,
        $sessionKey => [
            [
                'itemable_id' => $product->id,
                'itemable_type' => Product::class,
                'product_name' => $product->name,
                'product_price' => 10000,
                'quantity' => 2,
            ],
        ],
    ]);

    $component = Livewire::test(Checkout::class)
        ->set('shipping_region_id', $region->id);

    expect($component->get('subtotal'))->toBe(20000.0);
    expect($component->get('total'))->toBeGreaterThan(20000.0); // Incluye envío e IVA
});
