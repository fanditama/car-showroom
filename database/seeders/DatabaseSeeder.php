<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Cart;
use App\Models\Contact;
use App\Models\CreditApplication;
use App\Models\Promotion;
use App\Models\TestDrive;
use App\Models\Transaction;
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
        User::factory(10)->create();
        Car::factory(10)->create();
        Promotion::factory(10)->create();
        TestDrive::factory(10)->create();
        Transaction::factory(10)->create();
        CreditApplication::factory(10)->create();
        Contact::factory(10)->create();
        Cart::factory(10)->create();
    }
}
