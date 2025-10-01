<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'type' => $this->faker->randomElement(['billing', 'shipping']),
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'region_id' => \Database\Factories\RegionFactory::new(),
            'commune_id' => \Database\Factories\CommuneFactory::new(),
            'address_line_1' => $this->faker->streetAddress(),
            'address_line_2' => $this->faker->optional()->secondaryAddress(),
            'is_default' => false,
        ];
    }
}
