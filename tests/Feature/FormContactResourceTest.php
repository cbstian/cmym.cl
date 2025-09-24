<?php

use App\Filament\Resources\FormContacts\Pages\CreateFormContact;
use App\Filament\Resources\FormContacts\Pages\EditFormContact;
use App\Filament\Resources\FormContacts\Pages\ListFormContacts;
use App\Filament\Resources\FormContacts\Pages\ViewFormContact;
use App\Models\FormContact;
use App\Models\User;
use Livewire\Livewire;

test('puede ver la lista de contactos', function () {
    // Crear un usuario autenticado
    $user = User::factory()->create();
    $this->actingAs($user);

    // Crear algunos contactos de prueba
    $contacts = FormContact::factory()->count(3)->create();

    Livewire::test(ListFormContacts::class)
        ->assertCanSeeTableRecords($contacts)
        ->assertSuccessful();
});

test('puede crear un nuevo contacto', function () {
    // Crear un usuario autenticado
    $user = User::factory()->create();
    $this->actingAs($user);

    $contactData = [
        'nombre' => 'Juan Pérez',
        'correo' => 'juan@example.com',
        'telefono' => '123456789',
        'direccion' => 'Calle Principal 123',
        'mensaje' => 'Este es un mensaje de prueba',
    ];

    Livewire::test(CreateFormContact::class)
        ->fillForm($contactData)
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(FormContact::class, $contactData);
});

test('puede ver los detalles de un contacto', function () {
    // Crear un usuario autenticado
    $user = User::factory()->create();
    $this->actingAs($user);

    // Crear un contacto de prueba
    $contact = FormContact::factory()->create();

    Livewire::test(ViewFormContact::class, ['record' => $contact->getRouteKey()])
        ->assertSee($contact->nombre)
        ->assertSee($contact->correo)
        ->assertSee($contact->mensaje)
        ->assertSuccessful();
});

test('puede editar un contacto', function () {
    // Crear un usuario autenticado
    $user = User::factory()->create();
    $this->actingAs($user);

    // Crear un contacto de prueba
    $contact = FormContact::factory()->create();

    $updatedData = [
        'nombre' => 'Nombre Actualizado',
        'correo' => 'actualizado@example.com',
        'telefono' => '987654321',
        'direccion' => 'Nueva Dirección 456',
        'mensaje' => 'Mensaje actualizado',
    ];

    Livewire::test(EditFormContact::class, ['record' => $contact->getRouteKey()])
        ->fillForm($updatedData)
        ->call('save')
        ->assertNotified();

    $contact->refresh();

    expect($contact->nombre)->toBe($updatedData['nombre']);
    expect($contact->correo)->toBe($updatedData['correo']);
    expect($contact->mensaje)->toBe($updatedData['mensaje']);
});
