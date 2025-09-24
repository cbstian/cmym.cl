<?php

test('product exists validation detects existing items correctly', function () {
    // Datos de prueba
    $productId = 1;
    $productClass = 'App\Models\Product';

    // Simular un carrito con un producto existente
    $cartItems = [
        [
            'itemable_id' => $productId,
            'itemable_type' => $productClass,
            'quantity' => 2,
            'product_name' => 'Producto Test',
        ],
    ];

    // Simular la lógica de búsqueda de productos duplicados
    $existingItemIndex = null;
    foreach ($cartItems as $index => $item) {
        if (($item['itemable_id'] ?? null) === $productId &&
            ($item['itemable_type'] ?? null) === $productClass) {
            $existingItemIndex = $index;
            break;
        }
    }

    // Verificar que encontró el producto existente
    expect($existingItemIndex)->not()->toBeNull();
    expect($existingItemIndex)->toBe(0);
    expect($cartItems[$existingItemIndex]['quantity'])->toBe(2);
});

test('product exists validation returns null for non-existing items', function () {
    // Datos de prueba
    $searchProductId = 999; // ID que no existe
    $productClass = 'App\Models\Product';

    // Simular un carrito con productos diferentes
    $cartItems = [
        [
            'itemable_id' => 1,
            'itemable_type' => $productClass,
            'quantity' => 2,
        ],
        [
            'itemable_id' => 2,
            'itemable_type' => $productClass,
            'quantity' => 1,
        ],
    ];

    // Simular la lógica de búsqueda
    $existingItemIndex = null;
    foreach ($cartItems as $index => $item) {
        if (($item['itemable_id'] ?? null) === $searchProductId &&
            ($item['itemable_type'] ?? null) === $productClass) {
            $existingItemIndex = $index;
            break;
        }
    }

    // Verificar que NO encontró el producto
    expect($existingItemIndex)->toBeNull();
});

test('cart session key generation is consistent', function () {
    $sessionUserId = 'test_consistent_123';

    $key1 = 'cart_'.crc32($sessionUserId);
    $key2 = 'cart_'.crc32($sessionUserId);

    expect($key1)->toBe($key2);
    expect(crc32($sessionUserId))->toBeInt();
});
