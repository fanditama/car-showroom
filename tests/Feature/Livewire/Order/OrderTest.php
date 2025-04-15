<?php

use App\Livewire\Order\OrderForm;
use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'John Doe',
        'phone_number' => '081234567890',
        'address' => '123 Main Street, Jakarta',
    ]);

    $this->car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Camry',
        'year' => 2023,
        'price' => 350000000
    ]);

    actingAs($this->user);
});

test('dapat me-render order component', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->assertStatus(200);
});

test('halaman order form menapilkan detail mobil', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->assertSee('Toyota Camry')
        ->assertSee('2023')
        ->assertSee('350.000.000');
});

test('harga mobil ditampilkan dalam format mata uang dengan benar', function () {
    $car = Car::factory()->create([
        'price' => 123456789
    ]);

    Livewire::test(OrderForm::class, ['car' => $car])
        ->assertSee('123.456.789');
});

test('data pengguna sudah diisi sebelumnya dalam formulir tanpa koordinat', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->assertSet('name', 'John Doe')
        ->assertSet('phone', '081234567890')
        ->assertSet('address', '123 Main Street, Jakarta')
        ->assertSet('latitude', null)
        ->assertSet('longitude', null);
});

test('form validasi berfungsi', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('name', '')
        ->set('phone', 'not-a-number')
        ->set('address', 'short')
        ->set('payment_method', 'invalid_method')
        ->call('submitOrder')
        ->assertHasErrors(['name', 'phone', 'address', 'payment_method', 'latitude', 'longitude']);
});

test('toggles metode pembayaran mengubah opsi dengan benar', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('payment_method', 'transfer_bank')
        ->assertSet('selected_bank', null)
        ->assertSet('selected_card_type', null);

    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('payment_method', 'credit_card')
        ->assertSet('selected_bank', null)
        ->assertSet('selected_card_type', null);

    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('payment_method', 'cash')
        ->assertSet('showBankOptions', false)
        ->assertSet('showCardOptions', false)
        ->assertSet('selected_bank', null)
        ->assertSet('selected_card_type', null);
});

test('pembayaran cash(tunai) membuat transaksi dan me-redirect', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('name', 'John Doe')
        ->set('phone', '081234567890')
        ->set('address', 'A valid address that is long enough')
        ->set('latitude', -6.2)
        ->set('longitude', 106.8)
        ->set('payment_method', 'cash')
        ->call('submitOrder')
        ->assertDispatched('orderCreated');

    // cek jika transaksi telah dibuat
    assertDatabaseHas('transactions', [
        'user_id' => $this->user->id,
        'car_id' => $this->car->id,
        'total_amount' => 350000000,
        'payment_method' => 'cash',
        'status' => 'pending',
        'latitude' => -6.2,
        'longitude' => 106.8,
        'order_address' => 'A valid address that is long enough'
    ]);
});

test('pembayaran transfer bank membuat transaksi dan me-redirect', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('name', 'John Doe')
        ->set('phone', '081234567890')
        ->set('address', 'A valid address that is long enough')
        ->set('latitude', -6.2)
        ->set('longitude', 106.8)
        ->set('payment_method', 'transfer_bank')
        ->call('submitOrder')
        ->assertDispatched('orderCreated');

    // cek jika transaksi telah dibuat
    assertDatabaseHas('transactions', [
        'user_id' => $this->user->id,
        'car_id' => $this->car->id,
        'total_amount' => 350000000,
        'payment_method' => 'transfer_bank',
        'status' => 'pending',
        'latitude' => -6.2,
        'longitude' => 106.8,
        'order_address' => 'A valid address that is long enough'
    ]);
});

test('pembayaran credit card membuat transaksi dan me-redirect', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('name', 'John Doe')
        ->set('phone', '081234567890')
        ->set('address', 'A valid address that is long enough')
        ->set('latitude', -6.2)
        ->set('longitude', 106.8)
        ->set('payment_method', 'credit_card')
        ->call('submitOrder')
        ->assertDispatched('orderCreated');

    // cek jika transaksi telah dibuat
    assertDatabaseHas('transactions', [
        'user_id' => $this->user->id,
        'car_id' => $this->car->id,
        'total_amount' => 350000000,
        'payment_method' => 'credit_card',
        'status' => 'pending',
        'latitude' => -6.2,
        'longitude' => 106.8,
        'order_address' => 'A valid address that is long enough'
    ]);
});

test('pembayaran kartu kredit membuat transaksi dan me-redirect', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('name', 'John Doe')
        ->set('phone', '081234567890')
        ->set('address', 'A valid address that is long enough')
        ->set('latitude', -6.2)
        ->set('longitude', 106.8)
        ->set('payment_method', 'credit_card')
        ->call('submitOrder')
        ->assertDispatched('orderCreated');

    // cek jika transaksi telah dibuat
    assertDatabaseHas('transactions', [
        'user_id' => $this->user->id,
        'car_id' => $this->car->id,
        'total_amount' => 350000000,
        'payment_method' => 'credit_card',
        'status' => 'pending',
        'latitude' => -6.2,
        'longitude' => 106.8,
        'order_address' => 'A valid address that is long enough'
    ]);
});

test('update lokasi bekerja dengan benar', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->call('updateLocation', -6.18, 106.82, 'Jalan Sudirman 123, Jakarta')
        ->assertSet('latitude', -6.18)
        ->assertSet('longitude', 106.82)
        ->assertSet('address', 'Jalan Sudirman 123, Jakarta')
        ->assertDispatched('locationUpdated');
});

test('update bagian alamat dengan memanggil updateAddress method', function () {
    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->call('updateAddress', 'New Test Address 123')
        ->assertSet('address', 'New Test Address 123');
});

test('menyimpan data lokasi saat metode pembayaran berubah', function () {
    $initialLatitude = -6.18;
    $initialLongitude = 106.82;

    Livewire::test(OrderForm::class, ['car' => $this->car])
        ->set('latitude', $initialLatitude)
        ->set('longitude', $initialLongitude)
        ->set('payment_method', 'cash')
        ->assertSet('latitude', $initialLatitude)
        ->assertSet('longitude', $initialLongitude)
        ->set('payment_method', 'transfer_bank')
        ->assertSet('latitude', $initialLatitude)
        ->assertSet('longitude', $initialLongitude)
        ->assertDispatched('preserveMap');
});
