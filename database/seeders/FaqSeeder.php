<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        Faq::create([
            'title' => '¿El producto viene armado?',
            'text' => 'No, viene embalado y de facil armado.',
            'sort' => 1,
        ]);

        Faq::create([
            'title' => '¿El producto tiene garantía?',
            'text' => 'Si, tiene 3 meses de garantía.',
            'sort' => 2,
        ]);

        Faq::create([
            'title' => '¿El producto incluye despacho?',
            'text' => 'No, tiene un pequeño costo adicional.',
            'sort' => 3,
        ]);

        Faq::create([
            'title' => '¿Tienen  tienda física?',
            'text' => 'No contamos con tienda física, solo bodega.',
            'sort' => 4,
        ]);

        Faq::create([
            'title' => '¿Hacen envíos a Regiones?',
            'text' => 'Si, se hacen envíos por Starken, Chileexpress, Pullman cargo. Envíos por pagar.',
            'sort' => 5,
        ]);
    }
}
