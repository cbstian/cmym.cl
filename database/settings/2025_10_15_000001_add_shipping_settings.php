<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('ecommerce.shipping_costs_rm', []);

        // Empresas courier por defecto
        $this->migrator->add('ecommerce.courier_companies', [
            'Starken',
            'Chilexpress',
            'Correos de Chile',
            'Blue Express',
        ]);
    }

    public function down(): void
    {
        $this->migrator->delete('ecommerce.shipping_costs_rm');
        $this->migrator->delete('ecommerce.courier_companies');
    }
};
