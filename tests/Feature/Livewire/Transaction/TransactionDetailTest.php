<?php

use App\Livewire\Transaction\TransactionDetail;
use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

test('halaman detail transaksi dapat diakses oleh pemilik transaksi', function () {
    $user = User::factory()->create(['role' => 'user']);
    $car = Car::factory()->create();
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->get(route('transactions.show', $transaction))
        ->assertSuccessful()
        ->assertSeeLivewire(TransactionDetail::class);
});

test('halaman detail transaksi tidak dapat diakses oleh user lain', function () {
    $user = User::factory()->create(['role' => 'user']);
    $otherUser = User::factory()->create(['role' => 'user']);
    $car = Car::factory()->create();
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
    ]);

    $this->actingAs($otherUser)
        ->get(route('transactions.show', $transaction))
        ->assertForbidden();
});

test('halaman detail transaksi menampilkan informasi transaksi dengan benar', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Avanza',
        'year' => 2022,
        'price' => 250000000,
    ]);

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'transaction_date' => now()->format('d-m-Y H:i:s'),
        'total_amount' => 250000000,
        'payment_method' => 'transfer_bank',
        'status' => 'pending',
        'order_address' => 'Jl. Test No. 123, Jakarta',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->assertSee('Detail Transaksi')
        ->assertSee($transaction->id)
        ->assertSee('250.000.000')
        ->assertSee('Toyota Avanza')
        ->assertSee('Jl. Test No. 123, Jakarta')
        ->assertSee('Menunggu Pembayaran');
});

test('tombol bayar sekarang ditampilkan untuk transaksi pending dengan metode pembayaran online', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
        'payment_method' => 'transfer_bank',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->assertSee('Bayar Sekarang');
});

test('tombol konfirmasi pesanan ditampilkan untuk transaksi pending dengan metode pembayaran tunai', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
        'payment_method' => 'cash',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->assertSee('Konfirmasi Pesanan');
});

test('tombol batalkan pesanan ditampilkan untuk transaksi dengan status pending', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->assertSee('Batalkan Pesanan');
});

test('tombol batalkan pesanan tidak ditampilkan untuk transaksi dengan status selain pending', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $statuses = ['processing', 'success', 'failed', 'cancel'];

    foreach ($statuses as $status) {
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'status' => $status,
        ]);

        Livewire::actingAs($user)
            ->test(TransactionDetail::class, ['transaction' => $transaction])
            ->assertDontSee('Batalkan Pesanan');
    }
});

test('dapat membatalkan transaksi dengan status pending', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->call('cancelTransaction')
        ->assertDispatched('notify')
        ->assertDispatched('refreshTransactionStatus');

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'status' => 'cancel',
    ]);
});

test('tidak dapat membatalkan transaksi dengan status selain pending', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'processing',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->call('cancelTransaction')
        ->assertDispatched('notify');

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'status' => 'processing',
    ]);
});

test('dapat memproses pembayaran tunai', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
        'payment_method' => 'cash',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->call('processPayment')
        ->assertDispatched('notify')
        ->assertDispatched('refreshTransactionStatus');

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'status' => 'processing',
    ]);
});

test('menampilkan peta jika koordinat tersedia', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'order_address' => 'Jl. Test No. 123, Jakarta',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->assertSet('hasMap', true)
        ->assertSee('transaction-map');
});

test('tidak menampilkan peta jika koordinat tidak tersedia', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'latitude' => null,
        'longitude' => null,
        'order_address' => 'Jl. Test No. 123, Jakarta',
    ]);

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->assertSet('hasMap', false);
});

test('menangani callback pembayaran berhasil', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
        'payment_method' => 'transfer_bank',
    ]);

    $paymentResult = [
        'status_code' => 200,
        'transaction_id' => 'test-transaction-123',
        'order_id' => 'test-order-123',
        'payment_type' => 'bank_transfer',
    ];

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->call('handlePaymentCallback', $paymentResult)
        ->assertDispatched('notify')
        ->assertDispatched('refreshTransactionStatus');

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'status' => 'success',
    ]);
});

test('menangani callback pembayaran gagal', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
        'payment_method' => 'transfer_bank',
    ]);

    $paymentResult = [
        'status_code' => 400,
        'transaction_id' => 'test-transaction-123',
        'order_id' => 'test-order-123',
        'payment_type' => 'bank_transfer',
    ];

    Livewire::actingAs($user)
        ->test(TransactionDetail::class, ['transaction' => $transaction])
        ->call('handlePaymentCallback', $paymentResult)
        ->assertDispatched('notify')
        ->assertDispatched('refreshTransactionStatus');

    $this->assertDatabaseHas('transactions', [
        'id' => $transaction->id,
        'status' => 'failed',
    ]);
});
