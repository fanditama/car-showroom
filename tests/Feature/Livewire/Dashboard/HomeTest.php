<?php

use App\Livewire\Dashboard\Home;
use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    // buat user admin
    $this->admin = User::factory()->create([
        'role' => 'admin',
    ]);

    // buat beberapa mobil
    $this->cars = Car::factory()->count(5)->create();

    // buat beberapa user normal
    $this->users = User::factory()->count(3)->create([
        'role' => 'user',
    ]);

    // buat beberapa transaksi dengan status berbeda
    $this->transactions = collect();

    // transaksi berhasil dari 5 hari yang lalu
    $successful = Transaction::factory()->count(2)->create([
        'status' => 'success',
        'total_amount' => 100000000,
        'user_id' => $this->users->random()->id,
        'car_id' => $this->cars->random()->id,
        'created_at' => Carbon::now()->subDays(5),
    ]);
    $this->transactions = $this->transactions->merge($successful);

    // transaksi pending dari 2 hari yang lalu
    $pending = Transaction::factory()->count(1)->create([
        'status' => 'pending',
        'total_amount' => 75000000,
        'user_id' => $this->users->random()->id,
        'car_id' => $this->cars->random()->id,
        'created_at' => Carbon::now()->subDays(2),
    ]);
    $this->transactions = $this->transactions->merge($pending);

    // transaksi berhasil hari ini
    $today = Transaction::factory()->count(1)->create([
        'status' => 'success',
        'total_amount' => 120000000,
        'user_id' => $this->users->random()->id,
        'car_id' => $this->cars->random()->id,
        'created_at' => Carbon::now(),
    ]);
    $this->transactions = $this->transactions->merge($today);
});

test('admin dapat meng-akses halaman dasboard home', function () {
    actingAs($this->admin)
        ->get(route('dashboard.home'))
        ->assertStatus(200)
        ->assertSeeLivewire('dashboard.home');
});

test('non-admin tidak dapat meng-akses halaman dasboard home', function () {
    actingAs($this->users[0])
        ->get(route('dashboard.home'))
        ->assertRedirect(url('/'));
});

test('halaman dashboard home component memiliki data yang benar', function () {
    Livewire::actingAs($this->admin)
        ->test(Home::class)
        ->assertViewHas('totalUsers', User::count())
        ->assertViewHas('totalCars', Car::count())
        ->assertViewHas('totalTransactions', Transaction::count())
        ->assertViewHas('totalRevenue', Transaction::where('status', 'success')->sum('total_amount'));
});

test('halaman dashboard menampilkan statistik dengan benar', function () {
    Livewire::actingAs($this->admin)
        ->test(Home::class)
        ->assertSee('Total Pengguna')
        ->assertSee(User::count())
        ->assertSee('Total Mobil')
        ->assertSee(Car::count())
        ->assertSee('Total Transaksi')
        ->assertSee(Transaction::count())
        ->assertSee('Total Pendapatan')
        ->assertSee(number_format(Transaction::where('status', 'success')->sum('total_amount'), 0, ',', '.'));
});

test('halaman dashboard menampilkan transaksi terkini', function () {
    $recentTransactions = Transaction::with(['user', 'car'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $component = Livewire::actingAs($this->admin)
        ->test(Home::class)
        ->assertSee('Transaksi Terbaru');

    foreach ($recentTransactions as $transaction) {
        $component->assertSee($transaction->id)
                  ->assertSee($transaction->user->name);

        // Periksa status transaksi ditampilkan dengan benar
        if ($transaction->status == 'success') {
            $component->assertSee('Selesai');
        } elseif ($transaction->status == 'pending') {
            $component->assertSee('Tertunda');
        }
    }
});

test('halaman dashboard memiliki tampilan grafik transaksi', function () {
    Livewire::actingAs($this->admin)
        ->test(Home::class)
        ->assertSee('Transaksi 30 Hari Terakhir')
        ->assertSet('chartLabels', fn($value) => $value !== null)
        ->assertSet('chartData', fn($value) => $value !== null);
});

test('grafik transaksi data ter-cover selama 30 hari', function () {
    $component = Livewire::actingAs($this->admin)->test(Home::class);

    $chartData = json_decode($component->get('chartData'), true);
    $chartLabels = json_decode($component->get('chartLabels'), true);

    // Pastikan ada 30 titik data
    expect(count($chartLabels))->toBe(30);
    expect(count($chartData['counts']))->toBe(30);
    expect(count($chartData['amounts']))->toBe(30);

    // Pastikan tanggal transaksi yang kita buat ada dalam data
    $todayFormatted = Carbon::now()->format('d M');
    $fiveDaysAgoFormatted = Carbon::now()->subDays(5)->format('d M');

    // Cek apakah label tanggal ada dalam data
    expect($chartLabels)->toContain($todayFormatted);
    expect($chartLabels)->toContain($fiveDaysAgoFormatted);

    // Dapatkan indeks untuk tanggal hari ini dan 5 hari yang lalu
    $todayIndex = array_search($todayFormatted, $chartLabels);
    $fiveDaysAgoIndex = array_search($fiveDaysAgoFormatted, $chartLabels);

    // Pastikan jumlah transaksi untuk tanggal-tanggal tersebut benar
    expect($chartData['counts'][$todayIndex])->toBe(1); // 1 transaksi hari ini
    expect($chartData['counts'][$fiveDaysAgoIndex])->toBe(2); // 2 transaksi 5 hari yang lalu

    // Pastikan total pendapatan untuk tanggal-tanggal tersebut benar
    expect($chartData['amounts'][$todayIndex])->toBe(120000000); // 1 transaksi hari ini (120000000)
    expect($chartData['amounts'][$fiveDaysAgoIndex])->toBe(200000000); // 2 transaksi 5 hari yang lalu (2 x 100000000)
});

test('dashboard page has chart scripts loaded', function () {
    actingAs($this->admin)
        ->get(route('dashboard.home'))
        ->assertSee('https://cdn.jsdelivr.net/npm/chart.js'); // Cek script chart.js dimuat
});
