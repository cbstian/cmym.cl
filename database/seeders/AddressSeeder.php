<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Crear usuario y cliente test si no existe
        $user = User::firstOrCreate(
            ['email' => 'test@transbank.cl'],
            [
                'name' => 'Usuario Test Transbank',
                'phone' => '+56912345678',
                'password' => bcrypt('test123'),
            ]
        );

        $customer = Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'rut' => '11.111.111-1',
                'company_name' => null,
            ]
        );

        // Crear direcciones de prueba si no existen
        Address::firstOrCreate(
            ['customer_id' => $customer->id, 'type' => 'billing'],
            [
                'name' => 'Usuario Test',
                'phone' => '+56912345678',
                'region_id' => 1, // Asume que existe región
                'commune_id' => 1, // Asume que existe comuna
                'address_line_1' => 'Av. Test 123',
                'address_line_2' => 'Depto 456',
                'is_default' => true,
            ]
        );

        Address::firstOrCreate(
            ['customer_id' => $customer->id, 'type' => 'shipping'],
            [
                'name' => 'Usuario Test',
                'phone' => '+56912345678',
                'region_id' => 1, // Asume que existe región
                'commune_id' => 1, // Asume que existe comuna
                'address_line_1' => 'Av. Test 123',
                'address_line_2' => 'Depto 456',
                'is_default' => true,
            ]
        );

        $this->command->info('Direcciones de prueba creadas para Transbank');
    }
}
