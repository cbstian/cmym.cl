<?php

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can create a product with attributes through repeater', function () {
    $category = Category::factory()->create();

    $productData = [
        'name' => 'Test Product with Attributes',
        'category_id' => $category->id,
        'sku' => 'TEST-ATTR-001',
        'price' => '99.99',
        'short_description' => 'A test product',
        'description' => 'A detailed description',
        'is_active' => true,
        'is_featured' => false,
        'attributes' => [
            [
                'name' => 'Color',
                'slug' => 'color',
                'is_required' => true,
                'sort_order' => 1,
                'values' => ['Rojo', 'Azul', 'Verde'],
            ],
            [
                'name' => 'Talla',
                'slug' => 'talla',
                'is_required' => false,
                'sort_order' => 2,
                'values' => ['S', 'M', 'L', 'XL'],
            ],
        ],
    ];

    $component = Livewire::test(CreateProduct::class)
        ->fillForm($productData)
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Product::where('name', 'Test Product with Attributes')->count())->toBe(1);

    $product = Product::where('name', 'Test Product with Attributes')->first();
    expect($product->attributes)->toHaveCount(2);

    $colorAttribute = $product->attributes->where('name', 'Color')->first();
    expect($colorAttribute)->not->toBeNull()
        ->and($colorAttribute->slug)->toBe('color')
        ->and($colorAttribute->is_required)->toBeTrue()
        ->and($colorAttribute->values)->toBe(['Rojo', 'Azul', 'Verde']);

    $tallaAttribute = $product->attributes->where('name', 'Talla')->first();
    expect($tallaAttribute)->not->toBeNull()
        ->and($tallaAttribute->slug)->toBe('talla')
        ->and($tallaAttribute->is_required)->toBeFalse()
        ->and($tallaAttribute->values)->toBe(['S', 'M', 'L', 'XL']);
});

it('can edit a product and modify its attributes', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
    ]);

    // Crear atributos existentes
    $product->attributes()->createMany([
        [
            'name' => 'Color Original',
            'slug' => 'color-original',
            'is_required' => true,
            'sort_order' => 1,
            'values' => ['Rojo', 'Verde'],
        ],
    ]);

    $updatedData = [
        'name' => $product->name,
        'category_id' => $product->category_id,
        'sku' => $product->sku,
        'price' => $product->price,
        'attributes' => [
            [
                'id' => $product->attributes->first()->id,
                'name' => 'Color Modificado',
                'slug' => 'color-modificado',
                'is_required' => false,
                'sort_order' => 1,
                'values' => ['Azul', 'Amarillo', 'Negro'],
            ],
        ],
    ];

    Livewire::test(EditProduct::class, ['record' => $product->id])
        ->fillForm($updatedData)
        ->call('save');

    $product->refresh();
    $attribute = $product->attributes->first();

    expect($attribute->name)->toBe('Color Modificado')
        ->and($attribute->slug)->toBe('color-modificado')
        ->and($attribute->is_required)->toBeFalse()
        ->and($attribute->values)->toBe(['Azul', 'Amarillo', 'Negro']);
});
