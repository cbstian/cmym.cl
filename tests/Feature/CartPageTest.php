<?php

test('cart page loads successfully', function () {
    $response = $this->get('/carrito');

    $response->assertStatus(200);
    $response->assertViewIs('cart');
    $response->assertSee('Mi Carrito de Compras');
});

test('cart page shows empty cart when no items', function () {
    $response = $this->get('/carrito');

    $response->assertStatus(200);
    $response->assertSee('Tu carrito está vacío');
    $response->assertSee('Ver Productos');
});

test('cart page includes livewire components', function () {
    $response = $this->get('/carrito');

    $response->assertStatus(200);
    $response->assertSeeLivewire('cart.cart-counter');
    $response->assertSeeLivewire('cart.cart-manager');
});
