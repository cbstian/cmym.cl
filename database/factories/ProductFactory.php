<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);
        $price = fake()->randomFloat(2, 10, 1000);

        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(),
            'sku' => fake()->unique()->regexify('[A-Z]{3}[0-9]{4}'),
            'price' => $price,
            'sale_price' => fake()->boolean(30) ? $price * 0.8 : null,
            'weight' => fake()->randomFloat(2, 0.1, 50),
            'dimensions' => fake()->numberBetween(10, 50).' x '.fake()->numberBetween(10, 50).' x '.fake()->numberBetween(10, 50).' cm',
            'is_active' => fake()->boolean(80),
            'is_featured' => fake()->boolean(20),
            'image_primary_path' => 'products/'.fake()->uuid().'.jpg',
            'image_paths' => [
                'products/'.fake()->uuid().'.jpg',
                'products/'.fake()->uuid().'.jpg',
                'products/'.fake()->uuid().'.jpg',
            ],
        ];
    }
}
