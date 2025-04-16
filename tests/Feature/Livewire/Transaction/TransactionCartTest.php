<?php

use App\Livewire\Transaction\TransactionCart;
use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

test('komponent transaksi menampilkan data kosong untuk user guest (tamu)', function () {
    Livewire::test(TransactionCart::class)
        ->assertSee('Anda belum memiliki riwayat pemesanan.')
        ->assertSee('Lihat Mobil');
});

test('komponent tidak menampilkan transaksi untuk user guest (tamu)', function () {
    // buat satu user dan beberapa transaksi
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'order_id' => 'TEST-ORDER-123',
    ]);

    // test sebagai guest (tamu)
    Livewire::test(TransactionCart::class)
        ->assertDontSee('TEST-ORDER-123');
});

test('autentikasi user dapat melihat riwayat transaksi kosong', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TransactionCart::class)
        ->assertSee('Anda belum memiliki riwayat pemesanan.')
        ->assertSee('Lihat Mobil');
});

test('autentikasi user dapat melihat transaksi mereka', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create([
        'brand' => 'Toyota Avanza',
        'model' => 'Toyota',
        'type' => 'MPV'
    ]);

    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'order_id' => 'ORD-12345',
        'total_amount' => 250000000,
        'status' => 'pending',
        'payment_method' => 'transfer_bank',
        'transaction_date' => now()->format('d-m-Y H:i:s'),
        'payment_url' => 'https://payment.test/12345'
    ]);

    Livewire::actingAs($user)
        ->test(TransactionCart::class)
        ->assertSee('Riwayat Pemesanan')
        ->assertSee('ORD-12345')
        ->assertSee('250.000.000')
        ->assertSee('Pending')
        ->assertSee('Lanjutkan Pembayaran');
});

test('halaman transaksi menampilkan paginasi', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    // buat 15 transaksi (lebih dari 9 item per-halaman)
    Transaction::factory()->count(15)->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
    ]);

    Livewire::actingAs($user)
        ->test(TransactionCart::class)
        ->assertViewHas('transactions', function ($transactions) {
            return $transactions->count() == 9 && $transactions->total() == 15;
        });
});

test('user hanya melihat transaksi mereka saja', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $car = Car::factory()->create();

    $user1Transaction = Transaction::factory()->create([
        'user_id' => $user1->id,
        'car_id' => $car->id,
        'order_id' => 'USER1-ORD',
    ]);

    $user2Transaction = Transaction::factory()->create([
        'user_id' => $user2->id,
        'car_id' => $car->id,
        'order_id' => 'USER2-ORD',
    ]);

    Livewire::actingAs($user1)
        ->test(TransactionCart::class)
        ->assertSee('USER1-ORD')
        ->assertDontSee('USER2-ORD');
});

test('perbedaan status transaksi ditampilkan dengan benar', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    $statuses = ['pending', 'processing', 'success', 'cancel', 'failed'];

    foreach ($statuses as $status) {
        Transaction::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'status' => $status,
            'order_id' => "ORD-$status",
        ]);
    }

    $component = Livewire::actingAs($user)->test(TransactionCart::class);

    foreach ($statuses as $status) {
        $component->assertSee("ORD-$status")
            ->assertSee(ucfirst($status));
    }
});

test('melanjukan tombol pembayaran hanya untuk menampilkan transaksi yang berstatus pending dengan payment url', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create();

    // transaksi status pending dengan payment url
    $pendingTransaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
        'order_id' => 'ORD-PENDING',
        'payment_url' => 'https://payment.example.com/pending'
    ]);

    // lengkapi transaksi dengan payment url
    $completedTransaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'success',
        'order_id' => 'ORD-COMPLETED',
        'payment_url' => 'https://payment.example.com/completed'
    ]);

    // status transaksi pending tanpa payment url
    $pendingNoUrlTransaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'car_id' => $car->id,
        'status' => 'pending',
        'order_id' => 'ORD-NO-URL',
        'payment_url' => null
    ]);

    $component = Livewire::actingAs($user)->test(TransactionCart::class);

    // Pastikan tombol "Lanjutkan Pembayaran" muncul untuk transaksi pending dengan payment_url
    $component->assertSeeText('Lanjutkan Pembayaran')
                ->assertSee($pendingTransaction->payment_url);

    // Pastikan tombol "Lanjutkan Pembayaran" tidak muncul untuk transaksi dengan status success
    $component->assertDontSee($completedTransaction->payment_url);

    // Pastikan tombol "Lanjutkan Pembayaran" tidak muncul untuk transaksi pending tanpa payment_url
    $component->assertDontSee('Lanjutkan Pembayaran</a>', false);
});
