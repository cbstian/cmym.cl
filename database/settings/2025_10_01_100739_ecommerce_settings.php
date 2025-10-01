<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('ecommerce.emails_notifications_orders', ['sebastian@procodigo.cl']);
        $this->migrator->add('ecommerce.bank_details', 'Banco de Chile\nCuenta Corriente: 12345678-9\nRUT: 12.345.678-9\nTitular: CMYM SpA\nEmail de confirmación: pagos@cmym.cl');
        $this->migrator->add('ecommerce.email_confirmation_payment', 'sebastian@procodigo.cl');
    }
};
