<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'sebastian@procodigo.cl'],
            [
                'name' => 'Sebastián Aguilera',
                'password' => Hash::make('cba7492971'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'maicolpalma444@gmail.com'],
            [
                'name' => 'Michael Palma',
                'password' => Hash::make('96t65zix8d'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@cmym.cl'],
            [
                'name' => 'Administrador CMyM',
                'password' => Hash::make('oy1KwPBjHv'),
                'email_verified_at' => now(),
            ]
        );
    }
}
