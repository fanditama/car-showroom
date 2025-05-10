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
        $orderPrefix = 'ORDER-' . now()->format('Ym');
        $orderNumber = $this->faker->unique()->numberBetween(10000, 99999);
        $orderId = $orderPrefix . $orderNumber;

        return [
            'order_id' => $orderId,
            'transaction_date' => $this->faker->dateTimeBetween('-10 year', 'now')->format('d-m-Y H:i:s'),
            'total_amount' => $this->faker->randomFloat(2, 10000, 100000),
            'payment_method' => $this->faker->randomElement(['transfer_bank', 'credit_card', 'cash']),
            'status' => $this->faker->randomElement(['pending', 'processing', 'success', 'cancel', 'failed']),
            'latitude' => $this->faker->latitude(-7.9798, 112.6315),
            'longitude' => $this->faker->longitude(108.9861, 112.6315),
            'order_address' => $this->faker->address,
            'user_id' => \App\Models\User::factory(),
            'car_id' => \App\Models\Car::factory(),
            'payment_url' => $this->faker->url,
            'snap_token' => $this->faker->uuid,
        ];
    }

    /**
     * Set the transaction to pending status
     */
    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Set the transaction to processing status
     */
    public function processing(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'processing',
            ];
        });
    }

    /**
     * Set the transaction to success status
     */
    public function success(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'success',
                'payment_date' => now()->format('d-m-Y H:i:s'),
            ];
        });
    }

    /**
     * Set the transaction to cancel status
     */
    public function cancel(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancel',
            ];
        });
    }

    /**
     * Set the transaction to failed status
     */
    public function failed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'failed',
            ];
        });
    }
}
