<?php

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear un usuario administrador para las pruebas
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('has correct model class', function () {
    expect(OrderResource::getModel())->toBe(Order::class);
});

it('has correct navigation labels', function () {
    expect(OrderResource::getNavigationLabel())->toBe('Órdenes');
    expect(OrderResource::getModelLabel())->toBe('Orden');
    expect(OrderResource::getPluralModelLabel())->toBe('Órdenes');
});

it('can create an order', function () {
    $customer = Customer::factory()->create();

    $orderData = [
        'customer_id' => $customer->id,
        'status' => Order::STATUS_PENDING,
        'payment_status' => Order::PAYMENT_STATUS_PENDING,
        'subtotal' => 10000,
        'shipping_cost' => 2000,
        'discount_amount' => 1000,
        'total_amount' => 11000,
        'currency' => 'CLP',
    ];

    Livewire::test(CreateOrder::class)
        ->fillForm($orderData)
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Order::where('customer_id', $customer->id)->exists())->toBeTrue();
});

it('can list orders in the table', function () {
    $orders = Order::factory()->count(3)->create();

    Livewire::test(ListOrders::class)
        ->assertCanSeeTableRecords($orders);
});

it('can view an order', function () {
    $order = Order::factory()->create();

    $this->get(OrderResource::getUrl('view', ['record' => $order]))
        ->assertSuccessful();
});

it('can edit an order', function () {
    $order = Order::factory()->create();

    $this->get(OrderResource::getUrl('edit', ['record' => $order]))
        ->assertSuccessful();
});

it('displays order status badges correctly', function () {
    $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);

    Livewire::test(ListOrders::class)
        ->assertCanSeeTableRecords([$order])
        ->assertSee('Pendiente');
});

it('displays payment status badges correctly', function () {
    $order = Order::factory()->create(['payment_status' => Order::PAYMENT_STATUS_PAID]);

    Livewire::test(ListOrders::class)
        ->assertCanSeeTableRecords([$order])
        ->assertSee('Pagado');
});

it('can filter orders by status', function () {
    $pendingOrder = Order::factory()->create(['status' => Order::STATUS_PENDING]);
    $shippedOrder = Order::factory()->create(['status' => Order::STATUS_SHIPPED]);

    Livewire::test(ListOrders::class)
        ->filterTable('status', Order::STATUS_PENDING)
        ->assertCanSeeTableRecords([$pendingOrder])
        ->assertCanNotSeeTableRecords([$shippedOrder]);
});

it('can filter orders by payment status', function () {
    $paidOrder = Order::factory()->create(['payment_status' => Order::PAYMENT_STATUS_PAID]);
    $pendingOrder = Order::factory()->create(['payment_status' => Order::PAYMENT_STATUS_PENDING]);

    Livewire::test(ListOrders::class)
        ->filterTable('payment_status', Order::PAYMENT_STATUS_PAID)
        ->assertCanSeeTableRecords([$paidOrder])
        ->assertCanNotSeeTableRecords([$pendingOrder]);
});
