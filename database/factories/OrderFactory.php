<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(10000, 100000);
        $shippingCost = 0; // Free shipping
        $discountAmount = 0;
        $totalAmount = $subtotal + $shippingCost - $discountAmount;

        return [
            'order_number' => 'ORD-'.date('Ymd').'-'.str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'customer_id' => Customer::factory(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered']),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'currency' => 'CLP',
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
            'payment_method' => $this->faker->randomElement(['webpay', 'transfer']),
            'billing_address_id' => Address::factory(),
            'shipping_address_id' => Address::factory(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
