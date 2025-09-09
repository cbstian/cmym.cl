<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sillas',
                'slug' => 'sillas',
                'description' => 'Sillas para camping y exterior',
                'is_active' => true,
            ],
            [
                'name' => 'Estufas',
                'slug' => 'estufas',
                'description' => 'Estufas para camping y calefacción',
                'is_active' => true,
            ],
            [
                'name' => 'Toldos',
                'slug' => 'toldos',
                'description' => 'Toldos y refugios para camping',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
