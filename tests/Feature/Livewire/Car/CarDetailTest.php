<?php

use App\Livewire\Car\CarDetail;
use App\Models\Car;
use App\Models\Cart;
use App\Models\User;
use Livewire\Livewire;

it('berhasil me-render halaman', function () {
    $car = Car::factory()->create();
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->assertStatus(200);
});

it('mount dengan data mobil yang benar', function () {
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Avanza',
        'year' => 2023
    ]);
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->assertSet('car.id', $car->id)
        ->assertSet('car.brand', 'Toyota')
        ->assertSet('car.model', 'Avanza')
        ->assertSet('car.year', 2023);
});

it('mengaktifkan visibilitas popup berbagi', function () {
    $car = Car::factory()->create();
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->assertSet('showSharePopup', false)
        ->call('toggleSharePopup')
        ->assertSet('showSharePopup', true)
        ->call('toggleSharePopup')
        ->assertSet('showSharePopup', false);
});

it('bagikan dengan whatsapp', function () {
    $car = Car::factory()->create([
        'brand' => 'Honda',
        'model' => 'Civic',
        'year' => 2022
    ]);
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->call('shareToWhatsApp')
        ->assertDispatched('openUrl');
});

it('bagikan dengan facebook', function () {
    $car = Car::factory()->create();
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->call('shareToFacebook')
        ->assertDispatched('openUrl');
});

it('bagikan dengan instagram', function () {
    $car = Car::factory()->create();
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->call('shareToInstagram')
        ->assertDispatched('showInstagramHelp');
});

it('redirects ke halaman login jika mencoba melakukan order tetapi tidak terautentikasi', function () {
    auth()->logout();

    $car = Car::factory()->create();
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->call('order')
        ->assertRedirect(route('login'));
});

it('redirect ke formulir order jika pengguna yang diautentikasi mencoba melakukan order', function () {
    auth()->logout();

    $user = User::factory()->create();
    $car = Car::factory()->create();
    
    Livewire::actingAs($user)
        ->test(CarDetail::class, ['car' => $car])
        ->call('order')
        ->assertRedirect(route('order.form', ['car' => $car->id]));
});

it('tambahkan mobil ke cart ketika user diautentikasi dan mengirim notifikasi', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();
    
    Livewire::actingAs($user)
        ->test(CarDetail::class, ['car' => $car])
        ->assertSet('isInCart', false)
        ->call('addToCart')
        ->assertSet('isInCart', true)
        ->assertDispatched('notify')
        ->assertDispatched('cartUpdated');
    
    $this->assertDatabaseHas('carts', [
        'user_id' => $user->id,
        'car_id' => $car->id
    ]);
});

it('hapus mobil dari cart ketika diautentikasi dan mengirim notifikasi', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();
    
    // tambahkan mobil ke cart terlebih dahulu
    Cart::create([
        'user_id' => $user->id,
        'car_id' => $car->id
    ]);
    
    Livewire::actingAs($user)
        ->test(CarDetail::class, ['car' => $car])
        ->assertSet('isInCart', true)
        ->call('removeFromCart')
        ->assertSet('isInCart', false)
        ->assertDispatched('notify')
        ->assertDispatched('cartUpdated');
    
    $this->assertDatabaseMissing('carts', [
        'user_id' => $user->id,
        'car_id' => $car->id
    ]);
});

it('redirects ke halaman login jika mencoba menambahkan data ke cart tetapi tidak ter-autentikasi', function () {
    auth()->logout();
    $car = Car::factory()->create();
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->call('addToCart')
        ->assertRedirect(route('login'));
});

it('redirects ke halaman login jika mencoba menghapus data dari cart tetapi tidak ter-autentikasi', function () {
    auth()->logout();
    $car = Car::factory()->create();
    
    Livewire::test(CarDetail::class, ['car' => $car])
        ->call('removeFromCart')
        ->assertRedirect(route('login'));
});