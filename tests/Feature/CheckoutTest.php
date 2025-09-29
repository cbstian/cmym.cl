<?php

use App\Livewire\Checkout;
use App\Models\Location\Region;
use App\Models\Product;
use Database\Seeders\LocationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ejecutar el seeder de regiones para todos los tests
    $this->seed(LocationSeeder::class);
});

it('can display checkout page', function () {
    // Crear datos de prueba
    $product = Product::factory()->create([
        'price' => 10000,
        'stock_quantity' => 5,
        'is_active' => true,
    ]);

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
                'quantity' => 1,
                'attributes' => [],
            ],
        ],
    ]);

    $response = $this->get('/checkout');

    $response->assertStatus(200);
    $response->assertSeeLivewire(Checkout::class);
});

it('redirects to cart when cart is empty', function () {
    // No simular productos en el carrito - debe redirigir
    $response = $this->get('/checkout');

    $response->assertRedirect(route('cart'));
    $response->assertSessionHas('error', 'Tu carrito está vacío. Agrega productos antes de proceder al checkout.');
});

it('can process checkout with valid data', function () {
    // Crear datos de prueba
    $product = Product::factory()->create([
        'price' => 10000,
        'stock_quantity' => 5,
        'is_active' => true,
    ]);

    $region = Region::first();
    if (! $region) {
        $this->markTestSkipped('No hay regiones disponibles para el test');
    }

    // Obtener una comuna usando el método del modelo Region
    $communes = $region->communesActive();
    if (! $communes || $communes->isEmpty()) {
        $this->markTestSkipped('No hay comunas disponibles para el test');
    }
    $commune = $communes->first();

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
        ->set('payment_method', 'transfer') // Usar transferencia para evitar redirection a Webpay
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

    // El subtotal puede ser int o float, ambos son válidos
    expect($component->get('subtotal'))->toBe(20000);
    expect($component->get('total'))->toBeGreaterThan(20000); // Incluye envío
});

it('persists checkout data in session using livewire session attributes', function () {
    $product = Product::factory()->create(['price' => 5000]);

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
                'product_price' => $product->getPrice(),
                'quantity' => 1,
            ],
        ],
    ]);

    $region = Region::first();
    if (! $region) {
        $this->markTestSkipped('No hay regiones disponibles para el test');
    }

    $communes = $region->communesActive();
    if (! $communes || $communes->isEmpty()) {
        $this->markTestSkipped('No hay comunas disponibles para el test');
    }
    $commune = $communes->first();

    // Crear el componente y establecer datos
    $component = Livewire::test(Checkout::class)
        ->set('name', 'María González')
        ->set('email', 'maria@test.com')
        ->set('phone', '+56987654321')
        ->set('shipping_region_id', $region->id)
        ->set('shipping_commune_id', $commune->id)
        ->set('shipping_address_line_1', 'Calle Falsa 456')
        ->set('payment_method', 'webpay');

    // Verificar que los datos están en el componente
    expect($component->get('name'))->toBe('María González');
    expect($component->get('email'))->toBe('maria@test.com');

    // Los datos deben persistir en sesión gracias al atributo #[Session]
    // Crear un nuevo componente para simular una nueva carga de página
    $newComponent = Livewire::test(Checkout::class);

    // Los datos deben persistir desde la sesión
    expect($newComponent->get('name'))->toBe('María González');
    expect($newComponent->get('email'))->toBe('maria@test.com');
    expect($newComponent->get('phone'))->toBe('+56987654321');
    expect($newComponent->get('shipping_address_line_1'))->toBe('Calle Falsa 456');
    expect($newComponent->get('payment_method'))->toBe('webpay');
});

it('clears checkout session data when checkout service marks as complete', function () {
    // Establecer algunos datos en la sesión simulando un checkout anterior
    session([
        'livewire.app.livewire.checkout.name' => 'Test User',
        'livewire.app.livewire.checkout.email' => 'test@example.com',
    ]);

    // Verificar que hay datos
    expect(\App\Services\CheckoutService::hasCheckoutData())->toBeTrue();

    // Marcar como completado
    \App\Services\CheckoutService::markCheckoutComplete();

    // Verificar que los datos fueron limpiados
    expect(\App\Services\CheckoutService::hasCheckoutData())->toBeFalse();
    expect(session('livewire.app.livewire.checkout.name'))->toBeNull();
    expect(session('livewire.app.livewire.checkout.email'))->toBeNull();
});
