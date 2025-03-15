<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestDrive>
 */
class TestDriveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'testdrive_date' => now()->format('d-m-Y'),
            'status' => $this->faker->randomElement(['tertunda', 'disetujui', 'ditolak']),
            'user_id' => \App\Models\User::factory(),
            'car_id' => \App\Models\Car::factory(),
        ];
    }
}
