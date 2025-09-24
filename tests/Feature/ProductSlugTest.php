<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('slug is generated automatically when creating a product', function () {
    $category = Category::factory()->create();

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Mesa de Comedor Elegante',
        'description' => 'Una mesa de comedor elegante.',
        'price' => 299.99,
        'is_active' => true,
    ]);

    expect($product->slug)->toBe('mesa-de-comedor-elegante');
});

test('slug is not overwritten when provided manually', function () {
    $category = Category::factory()->create();

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Silla Moderna',
        'slug' => 'silla-personalizada',
        'description' => 'Una silla moderna.',
        'price' => 149.99,
        'is_active' => true,
    ]);

    expect($product->slug)->toBe('silla-personalizada');
});

test('slug is made unique when duplicate names exist', function () {
    $category = Category::factory()->create();

    // Crear primer producto
    $product1 = Product::create([
        'category_id' => $category->id,
        'name' => 'Lámpara de Mesa',
        'description' => 'Una lámpara de mesa.',
        'price' => 89.99,
        'is_active' => true,
    ]);

    // Crear segundo producto con el mismo nombre
    $product2 = Product::create([
        'category_id' => $category->id,
        'name' => 'Lámpara de Mesa',
        'description' => 'Otra lámpara de mesa.',
        'price' => 99.99,
        'is_active' => true,
    ]);

    expect($product1->slug)->toBe('lampara-de-mesa');
    expect($product2->slug)->toBe('lampara-de-mesa-1');
});

test('slug is regenerated when name changes and slug was not manually modified', function () {
    $category = Category::factory()->create();

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Estante Básico',
        'description' => 'Un estante básico.',
        'price' => 199.99,
        'is_active' => true,
    ]);

    expect($product->slug)->toBe('estante-basico');

    // Actualizar solo el nombre
    $product->update(['name' => 'Estante Premium']);

    expect($product->fresh()->slug)->toBe('estante-premium');
});

test('slug is not regenerated when manually modified during update', function () {
    $category = Category::factory()->create();

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Sofá Clásico',
        'description' => 'Un sofá clásico.',
        'price' => 899.99,
        'is_active' => true,
    ]);

    expect($product->slug)->toBe('sofa-clasico');

    // Actualizar tanto el nombre como el slug manualmente
    $product->update([
        'name' => 'Sofá Moderno',
        'slug' => 'sofa-premium-personalizado',
    ]);

    expect($product->fresh()->slug)->toBe('sofa-premium-personalizado');
});

test('slug handles special characters and spaces correctly', function () {
    $category = Category::factory()->create();

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Café & Té - Mesa Especial ñoña',
        'description' => 'Una mesa especial.',
        'price' => 399.99,
        'is_active' => true,
    ]);

    expect($product->slug)->toBe('cafe-te-mesa-especial-nona');
});

test('multiple products with similar names get unique slugs', function () {
    $category = Category::factory()->create();

    $products = [];
    for ($i = 1; $i <= 5; $i++) {
        $products[] = Product::create([
            'category_id' => $category->id,
            'name' => 'Silla de Oficina',
            'description' => "Silla de oficina número {$i}.",
            'price' => 150.00 + $i,
            'is_active' => true,
        ]);
    }

    expect($products[0]->slug)->toBe('silla-de-oficina');
    expect($products[1]->slug)->toBe('silla-de-oficina-1');
    expect($products[2]->slug)->toBe('silla-de-oficina-2');
    expect($products[3]->slug)->toBe('silla-de-oficina-3');
    expect($products[4]->slug)->toBe('silla-de-oficina-4');
});
