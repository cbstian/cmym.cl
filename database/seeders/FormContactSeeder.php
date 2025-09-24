<?php

namespace Database\Seeders;

use App\Models\FormContact;
use Illuminate\Database\Seeder;

class FormContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormContact::factory()->count(10)->create();
    }
}
