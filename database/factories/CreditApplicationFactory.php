<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditApplication>
 */
class CreditApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_date' => now()->format('d/m/Y H:i:s'),
            'income' => $this->faker->randomFloat(2, 10000, 100000),
            'status' => $this->faker->randomElement(['tertunda', 'disetujui', 'ditolak']),
            'user_id' => \App\Models\User::factory(),
            'car_id' => \App\Models\Car::factory(),
        ];
    }
}
