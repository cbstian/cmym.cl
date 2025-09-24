<?php

use App\Models\Category;
use App\Models\Product;

it('displays product details correctly', function () {
    // Crear una categoría
    $category = Category::factory()->create(['name' => 'Sillas']);

    // Crear un producto
    $product = Product::factory()->create([
        'name' => 'Silla de Prueba',
        'slug' => 'silla-de-prueba',
        'description' => 'Una excelente silla para pruebas',
        'short_description' => 'Silla cómoda y resistente',
        'price' => 100000,
        'sale_price' => 85000,
        'sku' => 'SILLA-001',
        'category_id' => $category->id,
        'is_active' => true,
    ]);

    $response = $this->get(route('product.show', $product->slug));

    $response->assertStatus(200)
        ->assertSee($product->name)
        ->assertSee('$85.000')
        ->assertSee('$100.000')
        ->assertSee($product->sku)
        ->assertSee($product->short_description)
        ->assertSee($category->name);
});

it('shows 404 for inactive products', function () {
    $product = Product::factory()->create([
        'slug' => 'producto-inactivo',
        'is_active' => false,
    ]);

    $this->get(route('product.show', $product->slug))
        ->assertStatus(404);
});

it('shows related products from same category', function () {
    $category = Category::factory()->create();

    $mainProduct = Product::factory()->create([
        'slug' => 'producto-principal',
        'category_id' => $category->id,
        'is_active' => true,
    ]);

    $relatedProduct = Product::factory()->create([
        'name' => 'Producto Relacionado',
        'category_id' => $category->id,
        'is_active' => true,
    ]);

    $response = $this->get(route('product.show', $mainProduct->slug));

    $response->assertStatus(200)
        ->assertSee('Productos Relacionados')
        ->assertSee($relatedProduct->name);
});

it('product grid links to individual products', function () {
    $product = Product::factory()->create([
        'name' => 'Producto con Link',
        'slug' => 'producto-con-link',
        'is_active' => true,
    ]);

    $response = $this->get(route('products'));

    $response->assertStatus(200)
        ->assertSee(route('product.show', $product->slug));
});
