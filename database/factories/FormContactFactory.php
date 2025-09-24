<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormContact>
 */
class FormContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'correo' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->optional()->phoneNumber(),
            'direccion' => $this->faker->optional()->address(),
            'mensaje' => $this->faker->paragraph(3),
            'reviewed' => $this->faker->boolean(30), // 30% de probabilidad de estar revisado
        ];
    }

    /**
     * Indicate that the contact has been reviewed.
     */
    public function reviewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'reviewed' => true,
        ]);
    }

    /**
     * Indicate that the contact has not been reviewed.
     */
    public function unreviewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'reviewed' => false,
        ]);
    }
}
