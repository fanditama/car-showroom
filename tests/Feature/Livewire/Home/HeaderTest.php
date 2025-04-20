<?php

use App\Livewire\Home\Header;
use App\Models\Car;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('berhasil dirender', function () {
    Livewire::test(Header::class)
        ->assertStatus(200);
});

it('menampilkan tipe mobil di navigasi', function () {
    Car::factory()->create(['type' => 'Sedan']);
    Car::factory()->create(['type' => 'SUV']);

    Livewire::test(Header::class)
        ->assertSee('sedan')
        ->assertSee('suv');
});

it('menampilkan judul "Showroom Mobil"', function () {
    Livewire::test(Header::class)
        ->assertSee('Showroom Mobil');
});

it('tautan ke filter tipe mobil berfungsi dengan benar', function () {
    // Create car types
    Car::factory()->create(['type' => 'Sedan']);
    Car::factory()->create(['type' => 'SUV']);

    Livewire::test(Header::class)
        ->assertSeeHtml('href="http://127.0.0.1:8000?type=sedan"')
        ->assertSeeHtml('href="http://127.0.0.1:8000?type=suv"');
});

it('menyorot tipe mobil yang dipilih', function () {
    Car::factory()->create(['type' => 'Sedan']);

    Livewire::withQueryParams(['type' => 'sedan'])
        ->test(Header::class)
        ->assertSeeHtml('font-bold text-gray-900');
});

it('menampilkan login dan register link ketika tidak terautentikasi', function () {
    Auth::logout();
    Livewire::test(Header::class)
        ->assertSee(route('login'))
        ->assertSee(route('register'))
        ->assertDontSee(route('account.settings'))
        ->assertDontSee(route('logout'));
});

it('menampilkan link nama user, pengaturan akun, dan logout ketika terautentikasi', function () {
    $user = User::factory()->create(['name' => 'Test User']);
    actingAs($user);

    Livewire::test(Header::class)
        ->assertSee('Test User')
        ->assertSee(route('account.settings'))
        ->assertSee(route('logout'))
        ->assertDontSee(route('login'))
        ->assertDontSee(route('register'));
});

it('menampilkan tautan dashboard admin untuk pengguna admin', function () {
    $admin = User::factory()->create([
        'name' => 'Admin User',
        'role' => 'admin'
    ]);
    actingAs($admin);

    Livewire::test(Header::class)
        ->assertSee('Dashboard Admin')
        ->assertSee(route('dashboard.home'));
});

it('tidak menampilkan tautan dashboard admin untuk pengguna non-admin', function () {
    $user = User::factory()->create([
        'name' => 'Regular User',
        'role' => 'user'
    ]);
    actingAs($user);

    Livewire::test(Header::class)
        ->assertDontSee('Dashboard Admin')
        ->assertDontSee(route('dashboard.home'));
});

it('menampilkan tautan riwayat pemesanan untuk pengguna reguler tetapi tidak untuk admin', function () {
    // Test reguler user
    $user = User::factory()->create([
        'name' => 'Regular User',
        'role' => 'user'
    ]);
    actingAs($user);

    Livewire::test(Header::class)
        ->assertSee('Riwayat Pemesanan')
        ->assertSee(route('transaction.cart'));

    // Test admin user
    $admin = User::factory()->create([
        'name' => 'Admin User',
        'role' => 'admin'
    ]);
    actingAs($admin);

    Livewire::test(Header::class)
        ->assertDontSee('Riwayat Pemesanan')
        ->assertDontSee(route('transaction.cart'));
});

it('menampilan inisial user\'s ketika terautentikasi', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    actingAs($user);

    Livewire::test(Header::class)
        ->assertSee('J');
});

it('tidak menampilkan inisial user\'s ketika tidak terautentikasi', function () {
    Auth::logout();
    Livewire::test(Header::class)
        ->assertSeeHtml('<svg class="h-5 w-5 text-gray-700 dark:text-gray-300"') // cek SVG
        ->assertDontSeeHtml('<span class="text-sm font-bold text-gray-700 dark:text-gray-300">J</span>'); // cek inisial user
});

it('link logout redirect ke route yang benar', function () {
    $user = User::factory()->create();
    actingAs($user);

    Livewire::test(Header::class)
        ->call('logout')
        ->assertRedirect('/');
});

it('menampilkan icon keranjang belanja', function () {
    Livewire::test(Header::class)
        ->assertSee('M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z');
});

it('menampilkan angka nol pada keranjang belanja', function () {
    Livewire::test(Header::class)
        ->assertSeeHtml('<span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>');
});

it('ubah penghitungan cart ketika cart di updated', function () {
    $user = User::factory()->create();
    actingAs($user);

    // buat beberapa cart item
    Cart::factory()->count(3)->create([
        'user_id' => $user->id
    ]);

    Livewire::test(Header::class)
        ->assertSet('cartItemCount', 3)
        ->call('updateCartCount', ['count' => 5])
        ->assertSet('cartItemCount', 5);
});

it('reset penghitungan cart ke nol ketika user logout', function () {
    $user = User::factory()->create();
    actingAs($user);

    // buat beberapa cart item
    Cart::factory()->count(3)->create([
        'user_id' => $user->id
    ]);

    Livewire::test(Header::class)
        ->assertSet('cartItemCount', 3)
        ->call('logout')
        ->assertSet('cartItemCount', 0);
});

it('listens untuk function cartUpdated event', function () {
    $user = User::factory()->create();
    actingAs($user);

    Livewire::test(Header::class)
        ->assertSet('cartItemCount', 0)
        ->dispatch('cartUpdated', ['count' => 7])
        ->assertSet('cartItemCount', 7);
});

it('dapat me-load dengan benar perhitungan cart dari database di function mount', function () {
    $user = User::factory()->create();
    actingAs($user);

    // Create some cart items
    Cart::factory()->count(4)->create([
        'user_id' => $user->id
    ]);

    Livewire::test(Header::class)
        ->assertSet('cartItemCount', 4);
});

it('menampilkan perhitungan cart di halaman view mobile dan dekstop', function () {
    $user = User::factory()->create();
    actingAs($user);

    Livewire::test(Header::class)
        ->assertSet('cartItemCount', 0)
        ->dispatch('cartUpdated', ['count' => 7])
        ->assertSet('cartItemCount', 7);
});

it('link ke halaman cart dengan benar', function () {
    Livewire::test(Header::class)
        ->assertSeeHtml('href="' . route('cart.index') . '"');
});

it('menjaga perhitungan cart ke nol untuk user tamu', function () {
    Auth::logout();

    Livewire::test(Header::class)
        ->assertSet('cartItemCount', 0)
        ->call('updateCartCount', ['count' => 5])
        ->assertSet('cartItemCount', 0);
});

it('tidak menampilkan ikon keranjang untuk pengguna admin', function () {
    $admin = User::factory()->create([
        'name' => 'Admin User',
        'role' => 'admin'
    ]);
    actingAs($admin);

    Livewire::test(Header::class)
        ->assertDontSee(route('cart.index'));
});
