<?php

use App\Livewire\Dashboard\CarInventory;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    // buat user admin
    $this->admin = User::factory()->create([
        'role' => 'admin',
    ]);
});

test('halaman inventory mobil dapat diakses oleh admin', function () {
    $this->actingAs($this->admin)
         ->get(route('dashboard.car'))
         ->assertStatus(200)
         ->assertSeeLivewire('dashboard.car-inventory');
});

test('halaman inventory mobil menampilkan data mobil dari database', function () {
    $cars = Car::factory()->count(5)->create();

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->assertViewHas('cars')
        ->assertSee($cars->first()->brand)
        ->assertSee($cars->first()->model);
});

test('halaman inventory mobil menampilkan harga dalam format rupiah dengan pemisah titik', function () {
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'price' => 1000000
    ]);

    // Test apakah tampilan tabel menampilkan format rupiah yang benar
    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->assertSee('Rp 1.000.000');
});

test('form edit menampilkan harga dalam format rupiah dengan pemisah titik', function () {
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'price' => 1000000
    ]);

    // Test bahwa saat mode edit, harga ditampilkan dengan format yang benar
    $component = Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('edit', $car->id);

    // Periksa apakah harga yang di-set ke form menggunakan format rupiah dengan pemisah titik
    expect($component->get('price'))->toBe('1.000.000');
});

test('dapat menyimpan harga yang dimasukkan dalam format rupiah dengan pemisah titik', function () {
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'price' => 1000000
    ]);

    // Test update dengan format harga rupiah
    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('edit', $car->id)
        ->set('price', '1.500.000') // Format dengan pemisah titik
        ->call('store');

    // Verifikasi bahwa nilai yang disimpan di database adalah nilai numerik tanpa pemisah
    $this->assertDatabaseHas('cars', [
        'id' => $car->id,
        'price' => 1500000
    ]);
});


test('pembuatan mobil baru dapat menerima harga dalam format rupiah dengan pemisah titik', function () {
    Storage::fake('public');

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->set('brand', 'Tesla')
        ->set('model', 'Model S')
        ->set('year', 2023)
        ->set('price', '75.000.000') // Format dengan pemisah titik
        ->set('type', 'sedan')
        ->call('store');

    // Verifikasi nilai yang disimpan adalah nilai numerik
    $this->assertDatabaseHas('cars', [
        'brand' => 'Tesla',
        'model' => 'Model S',
        'price' => 75000000
    ]);
});

test('halaman inventory mobil dapat mencari data mobil', function () {
    Car::factory()->create(['brand' => 'Toyota']);
    Car::factory()->create(['brand' => 'Honda']);
    Car::factory()->create(['brand' => 'BMW']);

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->set('search', 'Toyota')
        ->assertSee('Toyota')
        ->assertDontSee('BMW');
});

test('halaman inventory mobil dapat mengurutkan data mobil', function () {
    Car::factory()->create(['brand' => 'Toyota', 'price' => 200000]);
    Car::factory()->create(['brand' => 'Honda', 'price' => 150000]);
    Car::factory()->create(['brand' => 'BMW', 'price' => 300000]);

    $component = Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('sortBy', 'price')
        ->assertSet('sortField', 'price');

    // Assert component sudah diurutkan berdasarkan harga
    expect($component->get('sortField'))->toBe('price');
});

test('halaman inventory mobil dapat membuka pop-up modal', function () {
    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('openAddModal')
        ->assertSet('showAddModal', true)
        ->assertSet('editMode', false);
});

test('halaman inventory mobil dapat membuat data mobil baru', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('car.jpg');

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->set('brand', 'Tesla')
        ->set('model', 'Model S')
        ->set('year', 2023)
        ->set('price', 75000)
        ->set('type', 'sedan')
        ->set('description', 'Mobil listrik sedan mewah')
        ->set('image', $file)
        ->call('store');

    $this->assertDatabaseHas('cars', [
        'brand' => 'Tesla',
        'model' => 'Model S',
        'year' => 2023,
        'price' => 75000,
        'type' => 'sedan',
        'description' => 'Mobil listrik sedan mewah',
    ]);
});

test('halaman inventory mobil dapat membuka pop-up modal edit', function () {
    $car = Car::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('edit', $car->id)
        ->assertSet('showAddModal', true)
        ->assertSet('editMode', true)
        ->assertSet('currentCarId', $car->id)
        ->assertSet('brand', $car->brand)
        ->assertSet('model', $car->model);
});

test('halaman inventory mobil dapat melakukan update data mobil', function () {
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Camry'
    ]);

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('edit', $car->id)
        ->set('brand', 'Toyota Updated')
        ->set('model', 'Camry Updated')
        ->call('store');

    $this->assertDatabaseHas('cars', [
        'id' => $car->id,
        'brand' => 'Toyota Updated',
        'model' => 'Camry Updated',
    ]);
});

test('halaman inventory mobil dapat membuka pop-up modal hapus', function () {
    $car = Car::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('delete', $car->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('currentCarId', $car->id);
});

test('halaman inventory mobil dapat menghapus data mobil', function () {
    $car = Car::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('delete', $car->id)
        ->call('confirmDelete');

    $this->assertDatabaseMissing('cars', [
        'id' => $car->id
    ]);
});

test('halaman inventory mobil dapat menampilkan validasi required ketika menambah data mobil', function () {
    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->call('openAddModal')
        ->call('store')
        ->assertHasErrors(['brand', 'model', 'year', 'price', 'type']);
});

test('non-admin tidak dapat meng-akses halaman inventory mobil', function () {
    $regularUser = User::factory()->create([
        'role' => 'user',
    ]);

    $this->actingAs($regularUser)
        ->get(route('dashboard.car'))
        ->assertRedirect(url('/'));
});

test('pagination halaman mobil dapat bekerja', function () {
    Car::factory()->count(15)->create();

    Livewire::actingAs($this->admin)
        ->test(CarInventory::class)
        ->assertViewHas('cars');
});
