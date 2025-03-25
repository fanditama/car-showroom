<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\get;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\assertAuthenticated;

beforeEach(function () {
    auth()->logout();
});

test('Halaman login berhasil dimuat', function () {
    get(Login::getUrl())
        ->assertSuccessful()
        ->assertSee('Masuk');
});

test('pengguna dapat melakukan autentikasi dengan kredensial yang valid', function () {
    $user = User::factory()->create();
    
    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertRedirect(filament()->getHomeUrl());
    
    assertAuthenticated();
});

test('pengguna tidak dapat login dengan password yang salah', function () {
    $user = User::factory()->create();
    
    $livewireComponent = Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->call('authenticate');

    assertGuest();

    $livewireComponent->assertDontSeeHtml(filament()->getHomeUrl());
});

test('pengguna tidak dapat login dengan email yang salah', function () {
    $user = User::factory()->create();

    $livewireComponent = Livewire::test(Login::class)
        ->fillForm([
            'email' => 'nonexistent@example.com',
            'password' => 'wrong-password',
        ])
        ->call('authenticate');
    
    assertGuest();

    $livewireComponent->assertDontSeeHtml(filament()->getHomeUrl());
});

test('validasi email diperlukan pada form login', function () {
    $component = Livewire::test(Login::class)
        ->fillForm([
            'email' => '',
            'password' => 'password',
        ])
        ->call('authenticate');
    
    assertGuest();
    
    // Check if the form has validation errors
    $component->assertHasFormErrors(['email']);
});

test('validasi format email pada form login', function () {
    Livewire::test(Login::class)
        ->fillForm([
            'email' => 'not-an-email',
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email']);
    
    assertGuest();
});

test('validasi password diperlukan pada form login', function () {
    $user = User::factory()->create();
    
    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => '',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['password']);
    
    assertGuest();
});

test('fitur ingat saya berfungsi', function () {
    $user = User::factory()->create();
    
    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
            'remember' => true,
        ])
        ->call('authenticate')
        ->assertRedirect(filament()->getHomeUrl());
    
    assertAuthenticated();
});

test('halaman dashboard dilindungi dari akses tanpa login', function () {
    get(filament()->getHomeUrl())
        ->assertSuccessful()
        ->assertDontSee('Dashboard')
        ->assertSee('Masuk');
});

test('pengguna dapat melihat form login tanpa autentikasi', function () {
    get(filament()->getHomeUrl())
        ->assertSuccessful()
        ->assertSee('Masuk'); 
});

test('pengguna dapat melihat link pendaftaran di halaman login', function () {
    get(Login::getUrl())
        ->assertSee('Daftar Akun');
});
