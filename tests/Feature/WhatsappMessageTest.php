<?php

use App\Models\Category;
use App\Models\Product;

beforeEach(function () {
    $this->category = Category::factory()->create(['name' => 'Sillas']);
    $this->product = Product::factory()->create([
        'name' => 'Silla Ergonómica Premium',
        'slug' => 'silla-ergonomica-premium',
        'sku' => 'SILLA-001',
        'price' => 89990,
        'sale_price' => 79990,
        'category_id' => $this->category->id,
    ]);
});

it('generates a complete WhatsApp message with all product details', function () {
    $message = $this->product->getWhatsappMessage();

    expect($message)
        ->toContain('🛍️ *Hola! Me interesa este producto:*')
        ->toContain('📦 *Producto:* Silla Ergonómica Premium')
        ->toContain('💰 *Precio:* $79.990')
        ->toContain('🔢 *SKU:* SILLA-001')
        ->toContain('🏷️ *Categoría:* Sillas')
        ->toContain('🌐 *Ver producto:*')
        ->toContain('silla-ergonomica-premium')
        ->toContain('¿Podrías darme más información');
});

it('generates WhatsApp message without sale price when not available', function () {
    $product = Product::factory()->create([
        'name' => 'Mesa Simple',
        'price' => 50000,
        'sale_price' => null,
    ]);

    $message = $product->getWhatsappMessage();

    expect($message)->toContain('💰 *Precio:* $50.000');
});

it('generates WhatsApp message without SKU when not available', function () {
    $product = Product::factory()->create([
        'name' => 'Mesa Simple',
        'sku' => null,
    ]);

    $message = $product->getWhatsappMessage();

    expect($message)->not->toContain('🔢 *SKU:*');
});

it('generates WhatsApp message without category when not available', function () {
    $product = Product::factory()->create([
        'name' => 'Mesa Simple',
        'category_id' => null,
    ]);

    $message = $product->getWhatsappMessage();

    expect($message)->not->toContain('🏷️ *Categoría:*');
});

it('generates correct WhatsApp URL with encoded message', function () {
    $url = $this->product->getWhatsappUrl();

    expect($url)
        ->toStartWith('https://wa.me/56951589643?text=')
        ->toContain(urlencode('🛍️ *Hola! Me interesa este producto:*'))
        ->toContain(urlencode('Silla Ergonómica Premium'));
});

it('generates WhatsApp URL with custom phone number', function () {
    $customPhone = '56987654321';
    $url = $this->product->getWhatsappUrl($customPhone);

    expect($url)->toStartWith("https://wa.me/{$customPhone}?text=");
});

it('gets default WhatsApp phone number from configuration', function () {
    $phone = Product::getWhatsappPhoneNumber();

    expect($phone)->toBe('56951589643');
});

it('uses regular price when sale price is higher or equal', function () {
    $product = Product::factory()->create([
        'price' => 50000,
        'sale_price' => 50000,
    ]);

    $message = $product->getWhatsappMessage();

    expect($message)->toContain('💰 *Precio:* $50.000');
});

it('formats prices correctly with thousands separator', function () {
    $product = Product::factory()->create([
        'price' => 1234567,
        'sale_price' => null,
    ]);

    $message = $product->getWhatsappMessage();

    expect($message)->toContain('💰 *Precio:* $1.234.567');
});
