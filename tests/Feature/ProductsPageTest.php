<?php

it('displays products page correctly', function () {
    // Act: Visitar la página de productos
    $response = $this->get(route('products'));

    // Assert: Verificar que la página se carga correctamente
    $response
        ->assertOk()
        ->assertSee('NUESTROS PRODUCTOS')
        ->assertSee('Descubre nuestra amplia gama de productos')
        ->assertSee('¿No encuentras lo que buscas?')
        ->assertSee('CALIDAD GARANTIZADA')
        ->assertSee('VARIEDAD DE ESTILOS')
        ->assertSee('ENTREGA A DOMICILIO');
});

it('products page includes livewire component', function () {
    // Act
    $response = $this->get(route('products'));

    // Assert: Verificar que incluye el componente Livewire
    $response->assertSeeLivewire('product-grid');
});

it('products route is accessible', function () {
    // Act: Verificar que la ruta existe
    $response = $this->get('/productos');

    // Assert
    $response->assertOk();
});

it('menu shows active state for products page', function () {
    // Act
    $response = $this->get(route('products'));

    // Assert: Verificar que el menú muestra el estado activo
    $response->assertSee('productos*');
});

it('homepage links to products page', function () {
    // Act
    $response = $this->get(route('home'));

    // Assert: Verificar que hay enlace a productos
    $response->assertSee(route('products'));
});
