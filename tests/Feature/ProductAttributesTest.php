<?php

use App\Livewire\Cart\AddToCartButton;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('can display product attributes in product view', function () {
    // Crear categoría
    $category = Category::factory()->create();

    // Crear producto
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Producto con Atributos',
        'slug' => 'producto-con-atributos',
        'is_active' => true,
    ]);

    // Crear atributos
    $colorAttribute = Attribute::create([
        'product_id' => $product->id,
        'name' => 'Color',
        'is_required' => true,
        'sort' => 1,
        'values' => ['Rojo', 'Azul', 'Verde'],
    ]);

    $sizeAttribute = Attribute::create([
        'product_id' => $product->id,
        'name' => 'Tamaño',
        'is_required' => false,
        'sort' => 2,
        'values' => ['Pequeño', 'Mediano', 'Grande'],
    ]);

    // Visitar la página del producto
    $response = $this->get(route('product.show', $product->slug));

    $response->assertStatus(200);
    $response->assertSee('Color');
    $response->assertSee('Tamaño');
    $response->assertSee('Rojo');
    $response->assertSee('Azul');
    $response->assertSee('Verde');
});

it('can add product with attributes to cart', function () {
    // Crear categoría
    $category = Category::factory()->create();

    // Crear producto
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'stock_quantity' => 10,
    ]);

    // Crear atributo
    $attribute = Attribute::create([
        'product_id' => $product->id,
        'name' => 'Color',
        'is_required' => true,
        'sort' => 1,
        'values' => ['Rojo', 'Azul'],
    ]);

    // Probar el componente Livewire
    Livewire::test(AddToCartButton::class, ['product' => $product])
        ->set('selectedAttributes', [$attribute->id => 'Rojo'])
        ->set('quantity', 2)
        ->call('addToCart')
        ->assertHasNoErrors()
        ->assertRedirect(route('cart'));

    // Verificar que el producto esté en la sesión con atributos
    $sessionUserId = session('cart_user_id');
    $sessionKey = 'cart_'.crc32($sessionUserId);
    $cartItems = session($sessionKey, []);

    expect($cartItems)->toHaveCount(1);
    expect($cartItems[0]['attributes'])->toHaveKey('Color');
    expect($cartItems[0]['attributes']['Color'])->toBe('Rojo');
});

it('validates required attributes before adding to cart', function () {
    // Crear categoría
    $category = Category::factory()->create();

    // Crear producto
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'stock_quantity' => 10,
    ]);

    // Crear atributo requerido
    $attribute = Attribute::create([
        'product_id' => $product->id,
        'name' => 'Color',
        'is_required' => true,
        'sort' => 1,
        'values' => ['Rojo', 'Azul'],
    ]);

    // Recargar el producto con sus atributos
    $product = $product->fresh(['attributes']);

    // Intentar agregar sin seleccionar atributo requerido
    Livewire::test(AddToCartButton::class, ['product' => $product])
        ->set('selectedAttributes', [])
        ->set('quantity', 1)
        ->call('addToCart');

    // Verificar que el carrito permanece vacío (no se agregó nada por la validación)
    $sessionUserId = session('cart_user_id');
    if ($sessionUserId) {
        $sessionKey = 'cart_'.crc32($sessionUserId);
        $cartItems = session($sessionKey, []);
        expect($cartItems)->toBeEmpty();
    } else {
        // Si no hay sesión de carrito, significa que no se intentó agregar nada
        expect(session()->has('cart_user_id'))->toBe(false);
    }
});

it('treats products with different attributes as separate cart items', function () {
    // Crear categoría
    $category = Category::factory()->create();

    // Crear producto
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'stock_quantity' => 10,
    ]);

    // Crear atributo
    $attribute = Attribute::create([
        'product_id' => $product->id,
        'name' => 'Color',
        'is_required' => true,
        'sort' => 1,
        'values' => ['Rojo', 'Azul'],
    ]);

    // Agregar producto con color rojo
    Livewire::test(AddToCartButton::class, ['product' => $product])
        ->set('selectedAttributes', [$attribute->id => 'Rojo'])
        ->set('quantity', 1)
        ->call('addToCart');

    // Agregar mismo producto con color azul
    Livewire::test(AddToCartButton::class, ['product' => $product])
        ->set('selectedAttributes', [$attribute->id => 'Azul'])
        ->set('quantity', 1)
        ->call('addToCart');

    // Verificar que hay 2 items diferentes en el carrito
    $sessionUserId = session('cart_user_id');
    $sessionKey = 'cart_'.crc32($sessionUserId);
    $cartItems = session($sessionKey, []);

    expect($cartItems)->toHaveCount(2);
    expect($cartItems[0]['attributes']['Color'])->toBe('Rojo');
    expect($cartItems[1]['attributes']['Color'])->toBe('Azul');
});
