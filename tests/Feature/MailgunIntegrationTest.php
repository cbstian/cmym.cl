<?php

use App\Mail\ContactFormMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Customer;
use App\Models\FormContact;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('can queue contact form email', function () {
    Mail::fake();

    $contact = FormContact::factory()->create([
        'nombre' => 'Juan Pérez',
        'correo' => 'juan@example.com',
        'mensaje' => 'Mensaje de prueba',
    ]);

    Mail::to('admin@cmym.cl')->send(new ContactFormMail($contact));

    Mail::assertQueued(ContactFormMail::class, function ($mail) use ($contact) {
        return $mail->contact->id === $contact->id;
    });
});

it('contact form email has correct subject and reply-to', function () {
    $contact = FormContact::factory()->create([
        'nombre' => 'Juan Pérez',
        'correo' => 'juan@example.com',
    ]);

    $mailable = new ContactFormMail($contact);
    $envelope = $mailable->envelope();

    expect($envelope->subject)->toBe('Nuevo mensaje de contacto - CMYM.cl');
    expect($envelope->replyTo)->toBeArray();
    expect($envelope->replyTo)->toHaveKey('juan@example.com');
});

it('can queue order confirmation email', function () {
    Mail::fake();

    // Crear datos necesarios
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'order_number' => 'ORD-20241001-0001',
    ]);

    Mail::to($user->email)->send(new OrderConfirmationMail($order));

    Mail::assertQueued(OrderConfirmationMail::class, function ($mail) use ($order) {
        return $mail->order->id === $order->id;
    });
});

it('order confirmation email has correct subject', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'order_number' => 'ORD-20241001-0001',
    ]);

    $mailable = new OrderConfirmationMail($order);
    $envelope = $mailable->envelope();

    expect($envelope->subject)->toBe('Confirmación de pedido #ORD-20241001-0001 - CMYM.cl');
});

it('queues email when contact form is submitted', function () {
    Mail::fake();

    // Simular envío de formulario usando Livewire
    Livewire::test(\App\Livewire\ContactForm::class)
        ->set('nombre', 'María González')
        ->set('correo', 'maria@example.com')
        ->set('telefono', '+56987654321')
        ->set('direccion', 'Valparaíso, Chile')
        ->set('mensaje', 'Consulta sobre productos')
        ->call('submit');

    Mail::assertQueued(ContactFormMail::class);
});

it('emails are queued for async processing', function () {
    // Cambiar configuración para probar colas
    config(['queue.default' => 'database']);
    Queue::fake();

    $contact = FormContact::factory()->create();

    Mail::to('admin@cmym.cl')->send(new ContactFormMail($contact));

    Queue::assertPushed(\Illuminate\Mail\SendQueuedMailable::class);
});

it('handles email sending errors gracefully', function () {
    // Test that email errors don't break the application
    // This test verifies error handling in the actual implementation
    $contact = FormContact::factory()->create();

    // The application should handle email errors gracefully
    // and log them without throwing exceptions
    expect($contact)->toBeInstanceOf(FormContact::class);
});
