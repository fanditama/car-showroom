<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Favorite;
use App\Models\Promotion;
use App\Models\TestDrive;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Car::factory(10)->create();
        Promotion::factory(10)->create();
        TestDrive::factory(10)->create();
        Favorite::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
