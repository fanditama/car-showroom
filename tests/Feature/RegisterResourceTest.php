<?php

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Models\User;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use Livewire\Livewire;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseCount;

beforeEach(function () {
    auth()->logout();
});

test('halaman register berhasil dimuat', function () {
    get(Register::getUrl())
        ->assertSuccessful()
        ->assertSee('Daftar');
});

test('pengguna dapat melakukan registrasi dengan data yang valid', function () {
    $initialUserCount = User::count();
    
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('register')
        ->assertRedirect(Login::getUrl());
    
    assertGuest(); // memastikan pengguna tidak terautentikasi
    assertDatabaseCount('users', $initialUserCount + 1);
    assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

test('validasi nama diperlukan', function () {
    Livewire::test(Register::class)
        ->fillForm([
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('register')
        ->assertHasFormErrors(['name']);
    
        assertGuest(); // memastikan pengguna tidak terautentikasi
});

test('validasi email diperlukan', function () {
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('register')
        ->assertHasFormErrors(['email']);
    
    assertGuest(); // memastikan pengguna tidak terautentikasi
});

test('validasi format email', function () {
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'not-an-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('register')
        ->assertHasFormErrors(['email']);
    
    assertGuest(); // memastikan pengguna tidak terautentikasi
});

test('validasi password diperlukan', function () {
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ])
        ->call('register')
        ->assertHasFormErrors(['password']);
    
    assertGuest(); // memastikan pengguna tidak terautentikasi
});

test('validasi panjang password minimal 8 karakter', function () {
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ])
        ->call('register')
        ->assertHasFormErrors(['password']);
    
    assertGuest(); // memastikan pengguna tidak terautentikasi
});

test('validasi konfirmasi password harus cocok', function () {
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ])
        ->call('register')
        ->assertHasFormErrors(['password']);
    
    assertGuest(); // memastikan pengguna tidak terautentikasi
});

test('email harus unik', function () {
    $initialCount = User::count(); // menghitung jumlah pengguna sebelumnya

    User::factory()->create([
        'email' => 'existing@example.com'
    ]);

    assertDatabaseHas('users', ['email' => 'existing@example.com']);
    assertDatabaseCount('users', $initialCount + 1); 
    
    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'existing@example.com', 
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->call('register')
        ->assertHasFormErrors(['email']); 
    
    assertDatabaseCount('users', $initialCount + 1); // memastikan tidak ada pengguna baru yang terbuat
    assertGuest();
});

test('pengguna dapat menavigasi ke halaman login dari halaman pendaftaran', function () {
    get(Register::getUrl())
        ->assertSuccessful()
        ->assertSee('Kembali ke halaman Masuk');
});