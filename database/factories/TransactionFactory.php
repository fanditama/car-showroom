<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_date' => now()->format('d/m/Y H:i:s'),
            'total_amount' => $this->faker->randomFloat(2, 10000, 100000),
            'payment_method' => $this->faker->randomElement(['transfer_bank', 'credit_card', 'cash']),
            'status' => $this->faker->randomElement(['pending', 'success', 'cancel']),
            'user_id' => \App\Models\User::factory(),
            'car_id' => \App\Models\Car::factory(),
        ];
    }
}
