<?php

use App\Livewire\Dashboard\TransactionList;
use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    // buat user dengan role admin
    $this->admin = User::factory()->create([
        'role' => 'admin'
    ]);

    // login sebagai admin
    $this->actingAs($this->admin);
});

test('dapat me-render halaman daftar transaksi', function () {
    $response = $this->get(route('dashboard.transaction'));
    $response->assertStatus(200);
});

test('daftar transaksi komponen ada di halaman', function () {
    $this->get(route('dashboard.transaction'))
        ->assertSeeLivewire('dashboard.transaction-list');
});

test('dapat mengurutkan transaksi dengan field yang berbeda', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    Transaction::factory()->create([
        'order_id' => 'TRX-001',
        'user_id' => $user->id,
        'car_id' => $car->id,
        'transaction_date' => now()->subDays(2)->format('d-m-Y H:i:s'),
        'total_amount' => 100000000,
        'status' => 'pending'
    ]);

    Transaction::factory()->create([
        'order_id' => 'TRX-002',
        'user_id' => $user->id,
        'car_id' => $car->id,
        'transaction_date' => now()->subDays(1)->format('d-m-Y H:i:s'),
        'total_amount' => 200000000,
        'status' => 'success'
    ]);

    // test urutan berdasarkan order_id
    Livewire::test(TransactionList::class)
        ->call('sortBy', 'order_id')
        ->assertSet('sortField', 'order_id')
        ->assertSet('sortDirection', 'asc')
        ->call('sortBy', 'order_id')
        ->assertSet('sortField', 'order_id')
        ->assertSet('sortDirection', 'desc');

    // test urutan berdasarkan tanggal transaksi
    Livewire::test(TransactionList::class)
        ->call('sortBy', 'transaction_date')
        ->assertSet('sortField', 'transaction_date')
        ->assertSet('sortDirection', 'asc')
        ->call('sortBy', 'transaction_date')
        ->assertSet('sortField', 'transaction_date')
        ->assertSet('sortDirection', 'desc');

    // test berdasarkan total jumlah
    Livewire::test(TransactionList::class)
        ->call('sortBy', 'total_amount')
        ->assertSet('sortField', 'total_amount')
        ->assertSet('sortDirection', 'asc');
});

test('dapat mencari data transaksi', function () {
    $user = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    $car = Car::factory()->create(['brand' => 'Toyota', 'model' => 'Avanza']);

    // buat beberapa data test transaksi
    Transaction::factory()->create([
        'order_id' => 'TRX-001',
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending'
    ]);

    $otherCar = Car::factory()->create(['brand' => 'Honda', 'model' => 'Civic']);
    Transaction::factory()->create([
        'order_id' => 'TRX-002',
        'user_id' => $user->id,
        'car_id' => $otherCar->id,
        'status' => 'success'
    ]);

    // test pencarian berdasarkan order_id
    Livewire::test(TransactionList::class)
        ->set('search', 'TRX-001')
        ->assertSee('TRX-001')
        ->assertDontSee('TRX-002');

    // test pencarian berdasarkan nama user
    Livewire::test(TransactionList::class)
        ->set('search', 'John')
        ->assertSee('John Doe');

    // test pencarian berdasarkan brand mobil
    Livewire::test(TransactionList::class)
        ->set('search', 'Toyota')
        ->assertSee('Toyota')
        ->assertDontSee('Honda');
});

test('can filter transactions by status', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    // buat data transaksi dengan status yang berbeda
    Transaction::factory()->create([
        'order_id' => 'TRX-001',
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending'
    ]);

    Transaction::factory()->create([
        'order_id' => 'TRX-002',
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'success'
    ]);

    // test filter berdasarkan status
    Livewire::test(TransactionList::class)
        ->set('status', 'pending')
        ->assertSee('TRX-001')
        ->assertDontSee('TRX-002');

    Livewire::test(TransactionList::class)
        ->set('status', 'success')
        ->assertSee('TRX-002')
        ->assertDontSee('TRX-001');
});

test('dapat menampilkan detail transaksi', function () {
    $user = User::factory()->create(['name' => 'Jane Doe']);
    $car = Car::factory()->create(['brand' => 'Toyota', 'model' => 'Avanza']);

    $transaction = Transaction::factory()->create([
        'order_id' => 'TRX-001',
        'user_id' => $user->id,
        'car_id' => $car->id,
        'total_amount' => 150000000,
        'payment_method' => 'transfer_bank',
        'status' => 'success',
        'transaction_date' => now()->format('d-m-Y H:i:s'),
    ]);

    // test tampilan transaksi
    Livewire::test(TransactionList::class)
        ->call('viewTransaction', $transaction->id)
        ->assertSet('showModal', true)
        ->assertSet('selectedTransaction.id', $transaction->id)
        ->assertSee('Detail Transaksi')
        ->assertSee('TRX-001')
        ->assertSee('Jane Doe')
        ->assertSee('Toyota')
        ->assertSee('Avanza');

    // test tombol close (tutup modal)
    Livewire::test(TransactionList::class)
        ->set('showModal', true)
        ->set('selectedTransaction', $transaction)
        ->call('closeModal')
        ->assertSet('showModal', false)
        ->assertSet('selectedTransaction', null);
});

test('pagination bekerja dengan benar', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    // buat 15 data transaksi untuk test pagination (10 per halaman)
    Transaction::factory()->count(15)->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
    ]);

    Livewire::test(TransactionList::class)
        ->assertViewHas('transactions', function ($transactions) {
            return $transactions->total() === 15 && $transactions->count() === 10;
        });
});

test('saat melakukan pencarian data transaksi pagination akan di-reset', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();
    Transaction::factory()->count(15)->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
    ]);

    // test apakah saat proses update pencarian akan me-reset halaman
    $component = Livewire::test(TransactionList::class);
    $component->set('search', 'test')
        ->assertSet('page', 1);
});

test('saat melakukan update status transaksi pagination akan di-reset', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();
    Transaction::factory()->count(15)->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
    ]);

    // test apakah saat proses update status akan me-reset halaman
    $component = Livewire::test(TransactionList::class);
    $component->set('status', 'test')
        ->assertSet('page', 1);
});
