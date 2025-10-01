<?php

use Illuminate\Support\Facades\Mail;

test('mailgun command can show configuration', function () {
    $this->artisan('mailgun:test', ['type' => 'config'])
        ->expectsOutputToContain('📋 Mailgun Configuration:')
        ->expectsOutputToContain('Mail Driver:')
        ->expectsOutputToContain('Mailgun Domain:')
        ->assertExitCode(0);
});

test('mailgun command can send simple test email', function () {
    Mail::fake();

    $this->artisan('mailgun:test', [
        'type' => 'simple',
        '--email' => 'test@example.com',
    ])
        ->expectsOutputToContain('🚀 Testing Mailgun with type: simple to: test@example.com')
        ->expectsOutputToContain('✅ Email sent successfully!')
        ->assertExitCode(0);
});

test('mailgun command uses default email when none provided', function () {
    Mail::fake();

    $this->artisan('mailgun:test', ['type' => 'simple'])
        ->expectsOutputToContain('sebastian@procodigo.cl')
        ->assertExitCode(0);
});

test('mailgun command fails with invalid type', function () {
    $this->artisan('mailgun:test', ['type' => 'invalid'])
        ->expectsOutput('Unknown type: invalid. Available types: simple, contact, order, config')
        ->assertExitCode(1);
});
