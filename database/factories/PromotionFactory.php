<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(10),
            'discount' => $this->faker->randomFloat(2, 10000, 100000),
            'start_date' => now()->format('d-m-Y'),
            'end_date' => now()->addDays(7)->format('d-m-Y'),
            'car_id' => \App\Models\Car::factory(),
        ];
    }
}
