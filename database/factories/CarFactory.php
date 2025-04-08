<?php

namespace Database\Factories;

use Faker\Provider\FakeCar;
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
        $this->faker->addProvider(new FakeCar($this->faker));
        $vehicle = $this->faker->vehicleArray();

        return [
            'brand' => $vehicle['brand'],
            'model' => $vehicle['model'],
            'year' => $this->faker->biasedNumberBetween(1990, date('Y'), 'sqrt'),
            'price' => $this->faker->randomFloat(2, 10000, 100000),
            'type' => $this->faker->randomElement(['sedan', 'suv', 'MPV', 'hatchback', 'sport']),
            'description' => $this->faker->paragraph(1),
            'image_url' => null,
        ];
    }
}
