<?php

use App\Livewire\Order\CartIndex;
use App\Models\Car;
use App\Models\Cart;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user']);
});

test('dapat me-render halaman cart', function () {
    $this->actingAs($this->user)
        ->get(route('cart.index'))
        ->assertStatus(200)
        ->assertSeeLivewire('order.cart-index');
});

test('user dapat melihat pesan cart kosong ketika cart dalam keadaan kosong', function () {
    Livewire::actingAs($this->user)
        ->test(CartIndex::class)
        ->assertSee('Keranjang Anda kosong')
        ->assertSee('Tambahkan mobil ke keranjang untuk melanjutkan');
});

test('user dapat melihat item cart', function () {
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Avanza',
        'price' => 200000000
    ]);

    // tambah mobil ke cart
    Cart::create([
        'user_id' => $this->user->id,
        'car_id' => $car->id
    ]);

    Livewire::actingAs($this->user)
        ->test(CartIndex::class)
        ->assertSee('Toyota Avanza')
        ->assertSee('Rp 200.000.000')
        ->assertDontSee('Keranjang Anda kosong');
});

test('user dapat menghapus item dari cart', function () {
    $car = Car::factory()->create([
        'brand' => 'Honda',
        'model' => 'Jazz',
    ]);

    // tambah mobil ke cart
    $cart = Cart::create([
        'user_id' => $this->user->id,
        'car_id' => $car->id
    ]);

    expect(Cart::where('user_id', $this->user->id)->count())->toBe(1);

    Livewire::actingAs($this->user)
        ->test(CartIndex::class)
        ->call('removeItem', $cart->id)
        ->assertDispatched('notify')
        ->assertDispatched('cartUpdated');

    expect(Cart::where('user_id', $this->user->id)->count())->toBe(0);
});

test('cart menampilkan banyak item dengan benar', function () {
    $car1 = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Fortuner'
    ]);

    $car2 = Car::factory()->create([
        'brand' => 'Suzuki',
        'model' => 'Ertiga'
    ]);

    // tambah mobil ke cart
    Cart::create([
        'user_id' => $this->user->id,
        'car_id' => $car1->id
    ]);

    Cart::create([
        'user_id' => $this->user->id,
        'car_id' => $car2->id
    ]);

    Livewire::actingAs($this->user)
        ->test(CartIndex::class)
        ->assertSee('Toyota Fortuner')
        ->assertSee('Suzuki Ertiga');
});

test('menghapus cart item yang tidak ada menampilkan error', function () {
    Livewire::actingAs($this->user)
        ->test(CartIndex::class)
        ->call('removeItem', 999) // ID item tidak ada
        ->assertDispatched('notify', function ($event, $data) {
            return isset($data[0]) &&
                   $data[0]['type'] === 'error' &&
                   str_contains($data[0]['message'], 'Item tidak ditemukan');
        });
});

test('item cart user spesifik', function () {
    $anotherUser = User::factory()->create();

    $car = Car::factory()->create();

    // tambah mobil di kedua user
    Cart::create([
        'user_id' => $this->user->id,
        'car_id' => $car->id
    ]);

    Cart::create([
        'user_id' => $anotherUser->id,
        'car_id' => $car->id
    ]);

    // user pertama harus hanya melihat 1 item
    Livewire::actingAs($this->user)
        ->test(CartIndex::class)
        ->assertCount('cartItems', 1);

    // user kedua harus hanya melihat 1 item
    Livewire::actingAs($anotherUser)
        ->test(CartIndex::class)
        ->assertCount('cartItems', 1);
});

test('function refreshCart melakukan update cart item', function () {
    // chart awal kosong
    Livewire::actingAs($this->user)
        ->test(CartIndex::class)
        ->assertCount('cartItems', 0);

    // tambah item ke cart
    $car = Car::factory()->create();
    Cart::create([
        'user_id' => $this->user->id,
        'car_id' => $car->id
    ]);

    // panggil refreshCart
    Livewire::actingAs($this->user)
        ->test(CartIndex::class)
        ->call('refreshCart')
        ->assertCount('cartItems', 1);
});
