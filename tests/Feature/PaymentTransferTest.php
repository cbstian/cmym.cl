<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Settings\EcommerceSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Configurar settings de ecommerce para las pruebas
    $settings = app(EcommerceSettings::class);
    $settings->emails_notifications_orders = ['ventas@cmym.cl'];
    $settings->bank_details = "Banco de Chile\nCuenta Corriente: 123456789\nRUT: 12.345.678-9\nTitular: CMYM SpA";
    $settings->save();
});

it('displays transfer instructions page for transfer payment orders', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'payment_method' => 'transfer',
        'payment_status' => Order::PAYMENT_STATUS_PENDING,
        'total_amount' => 50000,
    ]);

    $response = $this->get(route('payment.transfer.instructions', ['order' => $order->id]));

    $response->assertSuccessful();
    $response->assertSee($order->order_number, false);
    $response->assertSee('Instrucciones', false);
    $response->assertSee('Banco de Chile', false);
    $response->assertSee('ventas@cmym.cl', false);
    $response->assertSee(number_format($order->total_amount, 0, ',', '.'), false);
});

it('redirects if order is not a transfer payment', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'payment_method' => 'webpay',
        'payment_status' => Order::PAYMENT_STATUS_PENDING,
    ]);

    $response = $this->get(route('payment.transfer.instructions', ['order' => $order->id]));

    $response->assertRedirect(route('home'));
    $response->assertSessionHasErrors('payment');
});

it('redirects if order payment status is not pending', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'payment_method' => 'transfer',
        'payment_status' => Order::PAYMENT_STATUS_PAID,
    ]);

    $response = $this->get(route('payment.transfer.instructions', ['order' => $order->id]));

    $response->assertRedirect(route('home'));
    $response->assertSessionHas('info');
});

it('shows order items and shipping address in transfer instructions', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    $shippingAddress = \App\Models\Address::factory()->create([
        'customer_id' => $customer->id,
        'type' => 'shipping',
    ]);

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'payment_method' => 'transfer',
        'payment_status' => Order::PAYMENT_STATUS_PENDING,
        'shipping_address_id' => $shippingAddress->id,
    ]);

    // Crear items manualmente
    $item = \App\Models\OrderItem::factory()->create([
        'order_id' => $order->id,
    ]);

    $response = $this->get(route('payment.transfer.instructions', ['order' => $order->id]));

    $response->assertSuccessful();
    $response->assertSee($shippingAddress->address_line_1);
    $response->assertSee($item->product_name);
});
