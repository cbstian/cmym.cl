<?php

use App\Livewire\ProductGrid;
use App\Models\Product;
use Livewire\Livewire;

it('uses custom pagination view', function () {
    // Act
    $component = new ProductGrid;

    // Assert: Verificar que usa la vista personalizada
    expect($component->paginationView())
        ->toBe('vendor.livewire.custom-bootstrap');
});

it('displays products with custom pagination elements', function () {
    // Act: Renderizar el componente ProductGrid
    $component = Livewire::test(ProductGrid::class);

    // Assert: Verificar que se muestra el título y elementos básicos
    $component
        ->assertSee('PRODUCTOS')
        ->assertStatus(200);

    // Si hay productos, verificar elementos de paginación
    $productsCount = Product::where('is_active', true)->count();
    if ($productsCount > 4) {
        $component
            ->assertSee('Mostrando') // Texto del paginador personalizado
            ->assertSee('productos'); // Texto del paginador personalizado
    }
});

it('can navigate between pages', function () {
    // Verificar que hay suficientes productos para la paginación
    $productsCount = Product::where('is_active', true)->count();

    if ($productsCount <= 4) {
        // Si no hay suficientes productos, crear algunos
        for ($i = $productsCount + 1; $i <= 10; $i++) {
            Product::create([
                'name' => "Test Product $i",
                'description' => "Test description $i",
                'short_description' => 'Test short description',
                'price' => 25000,
                'category_id' => 1,
                'is_active' => true,
                'is_featured' => false,
                'sku' => 'TEST-'.str_pad($i, 3, '0', STR_PAD_LEFT),
                'image_primary_path' => 'products/test.jpg',
                'weight' => 1.5,
                'dimensions' => '50x50x20 cm',
            ]);
        }
    }

    // Act: Renderizar el componente
    $component = Livewire::test(ProductGrid::class);

    // Assert: Verificar navegación
    $component
        ->assertSee('Mostrando 1 a 4')
        ->call('nextPage')
        ->assertSee('Mostrando 5 a 8');
});

it('displays correct pagination information', function () {
    // Verificar que tenemos productos suficientes
    $totalProducts = Product::where('is_active', true)->count();

    if ($totalProducts < 8) {
        $this->markTestSkipped('No hay suficientes productos para probar la paginación');
    }

    // Act
    $component = Livewire::test(ProductGrid::class);

    // Assert
    $component
        ->assertSee('Mostrando')
        ->assertSee('productos')
        ->assertSee('1')
        ->assertSee('4');

    // Si hay más de una página, verificar botón siguiente
    if ($totalProducts > 4) {
        $component->assertSee('Siguiente');
    }
});
