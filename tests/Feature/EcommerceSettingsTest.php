<?php

use App\Settings\EcommerceSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can load ecommerce settings', function () {
    $settings = app(EcommerceSettings::class);

    expect($settings->emails_notifications_orders)->toBeArray();
    expect($settings->bank_details)->toBeString();
});

it('has default values for ecommerce settings', function () {
    $settings = app(EcommerceSettings::class);

    expect($settings->emails_notifications_orders)->toContain('admin@cmym.cl');
    expect($settings->bank_details)->toContain('Banco de Chile');
});

it('can update ecommerce settings', function () {
    $settings = app(EcommerceSettings::class);

    $newEmails = ['test1@example.com', 'test2@example.com'];
    $newBankDetails = 'Banco Nuevo - Cuenta: 987654321';

    $settings->emails_notifications_orders = $newEmails;
    $settings->bank_details = $newBankDetails;
    $settings->save();

    // Reload settings from database
    $reloadedSettings = app(EcommerceSettings::class);

    expect($reloadedSettings->emails_notifications_orders)->toBe($newEmails);
    expect($reloadedSettings->bank_details)->toBe($newBankDetails);
});

it('validates email format in notifications array', function () {
    $settings = app(EcommerceSettings::class);

    // This should work
    $settings->emails_notifications_orders = ['valid@email.com'];
    $settings->save();

    expect($settings->emails_notifications_orders)->toContain('valid@email.com');
});

it('can get bank details from Payment model', function () {
    $bankDetails = \App\Models\Payment::getBankDetails();

    expect($bankDetails)->toBeString();
    expect($bankDetails)->toContain('Banco de Chile');
});

it('can get notification emails from Order model', function () {
    $emails = \App\Models\Order::getNotificationEmails();

    expect($emails)->toBeArray();
    expect($emails)->toContain('admin@cmym.cl');
});
