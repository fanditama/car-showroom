<?php

use App\Livewire\Home\Header;
use App\Models\Car;
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

it('menampilan inisial user\'s ketika terautentikasi', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    actingAs($user);

    Livewire::test(Header::class)
        ->assertSee('J');
});

it('tidak menampilkan inisial user\'s ketika tidak terautentikasi', function () {
    Auth::logout();
    Livewire::test(Header::class)
        ->assertDontSee('J');
});

it('link logout redirect ke route yang benar', function () {
    $user = User::factory()->create();
    actingAs($user);

    Livewire::test(Header::class)
        ->call('logout')
        ->assertRedirect('/');
});
