<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand' => $this->faker->company(),
            'model' => $this->faker->word(),
            'year' => $this->faker->year(),
            'price' => $this->faker->randomFloat(2, 10000, 100000),
            'type' => $this->faker->randomElement(['sedan', 'suv', 'MPV', 'hatchback', 'sport']),
            'description' => $this->faker->paragraph(1),
            'image_url' => null,
        ];
    }
}
