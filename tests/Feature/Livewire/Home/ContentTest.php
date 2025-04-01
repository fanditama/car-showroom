<?php

use App\Livewire\Home\Content;
use App\Models\Car;
use Livewire\Livewire;

test('dapat render component content', function () {
    Livewire::test(Content::class)
        ->assertStatus(200);
});

test('menampilkan pesan tidak ada mobil saat data mobil kosong', function () {
    Livewire::test(Content::class)
        ->assertSee('Tidak ditemukan mobil pada kategori ini.');
});

test('menampilkan mobil dengan detail yang benar', function () {
    // Create test car
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Avanza',
        'price' => 150000000,
    ]);

    Livewire::test(Content::class)
        ->assertSee('Toyota')
        ->assertSee('Avanza')
        ->assertSee(number_format(150000000, 0, '.', '.'));
});

test('dapat mem-filter mobil berdasarkan jenis', function () {
    // Create cars with different types
    $sedan = Car::factory()->create([
        'brand' => 'Honda',
        'model' => 'Civic',
        'type' => 'sedan',
    ]);

    $suv = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'consequatur',
        'type' => 'suv',
    ]);

    // Test filtering by sedan type
    Livewire::test(Content::class, ['type' => 'sedan'])
        ->assertSee('Honda')
        ->assertSee('Civic')
        ->assertDontSee('Fortuner')
        ->assertSee('Sedan Mobil');
});

test('dapat mengurutkan mobil berdasarkan harga terendah', function () {
    // Create cars with different prices
    $expensive = Car::factory()->create([
        'brand' => 'BMW',
        'model' => 'X5',
        'price' => 800000000,
    ]);

    $cheap = Car::factory()->create([
        'brand' => 'Daihatsu',
        'model' => 'Ayla',
        'price' => 120000000,
    ]);

    Livewire::test(Content::class)
        ->set('sortBy', 'price_asc')
        ->assertSeeInOrder(['Daihatsu', 'BMW']);
});

test('dapat mengurutkan mobil berdasarkan harga tertinggi', function () {
    // Create cars with different prices
    $expensive = Car::factory()->create([
        'brand' => 'BMW',
        'model' => 'X5',
        'price' => 800000000,
    ]);

    $cheap = Car::factory()->create([
        'brand' => 'Daihatsu',
        'model' => 'Ayla',
        'price' => 120000000,
    ]);

    Livewire::test(Content::class)
        ->set('sortBy', 'price_desc')
        ->assertSeeInOrder(['BMW', 'Daihatsu']);
});

test('me-reset pagination ketika pengurutan berubah', function () {
    // Create multiple cars to trigger pagination
    Car::factory()->count(18)->create();

    $component = Livewire::test(Content::class);
        $component->call('nextPage');
        $firstCarOnPageTwo = Car::query()->latest()->skip(9)->first();
        $component->assertSee($firstCarOnPageTwo->model);
        $component->set('sortBy', 'price_desc');
        $firstCarAfterSort = Car::query()->orderBy('price', 'desc')->first();
        $component->assertSee($firstCarAfterSort->model);
});

test('menampilkan detail link mobil', function () {
    $car = Car::factory()->create();

    Livewire::test(Content::class)
        ->assertSee('Detail Tampilan')
        ->assertSee(route('cars.show', $car->id));
});

test('menampilkan gambar default ketika tidak ada url gambar yang diberikan', function () {
    Car::factory()->create(['image_url' => null]);

    Livewire::test(Content::class)
        ->assertSee('fas fa-car text-gray-600 text-4xl');
});
