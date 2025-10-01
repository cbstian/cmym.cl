<?php

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can render the products list page', function () {
    Livewire::test(ListProducts::class)
        ->assertSuccessful();
});

it('can list products', function () {
    $category = Category::factory()->create();
    $products = Product::factory()->count(3)->create(['category_id' => $category->id]);

    Livewire::test(ListProducts::class)
        ->assertCanSeeTableRecords($products);
});

it('can search products', function () {
    $category = Category::factory()->create();
    $productA = Product::factory()->create([
        'name' => 'Product Alpha',
        'category_id' => $category->id,
    ]);
    $productB = Product::factory()->create([
        'name' => 'Product Beta',
        'category_id' => $category->id,
    ]);

    Livewire::test(ListProducts::class)
        ->searchTable('Alpha')
        ->assertCanSeeTableRecords([$productA])
        ->assertCanNotSeeTableRecords([$productB]);
});

it('can create a product with stock', function () {
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'category_id' => $category->id,
        'stock_quantity' => 25,
    ]);

    expect($product->stock_quantity)->toBe(25);
});

it('form has stock quantity field', function () {
    Livewire::test(CreateProduct::class)
        ->assertFormFieldExists('stock_quantity');
});
