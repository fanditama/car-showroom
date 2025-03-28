<?php

use App\Models\User;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('menampilkan halaman register', function () {
    get('/register')
        ->assertStatus(200)
        ->assertViewIs('auth.register');
});

it('registrasi user baru', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    post('/register', $userData);

    assertAuthenticated();
    assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

it('validasi nama diperlukan', function () {
    $userData = [
        'name' => '',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    post('/register', $userData)
        ->assertSessionHasErrors(['name']);
});

it('validasi valid email diperlukan', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    post('/register', $userData)
        ->assertSessionHasErrors(['email']);
});

it('validasi email unik diperlukan', function () {
    // buat user dulu
    User::factory()->create(['email' => 'existing@example.com']);

    $userData = [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    post('/register', $userData)
        ->assertSessionHasErrors(['email']);
});

it('validasi password diperlukan', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => '',
        'password_confirmation' => 'password',
    ];

    post('/register', $userData)
        ->assertSessionHasErrors(['password']);
});

it('diperlukan validasi kecocokan antara password dan password konfirmasi', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'different',
    ];

    post('/register', $userData)
        ->assertSessionHasErrors(['password']);
});

it('user baru berhasil melakukan regiter dan muncul notifikasi pesan berhasil', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    post('/register', $userData)
        ->assertSessionHas('toast_success', 'Selamat, akun anda berhasil dibuat!');

    assertAuthenticated();
    assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});
