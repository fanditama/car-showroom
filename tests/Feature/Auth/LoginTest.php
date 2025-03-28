<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withSession;

it('menampilkan halaman login', function () {
    get('/login')
        ->assertStatus(200)
        ->assertViewIs('auth.login');
});

it('mengijinkan pengguna melakukan autentikasi menggunakan form login', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    assertAuthenticated();
    $this->assertAuthenticatedAs($user);
    $this->actingAs($user);

    $this->get('/account/settings')->assertOk();
    $this->post('/logout');
    assertGuest();
});

it('tidak mengijinkan user melakukan autentikasi dengan password yang salah', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => '$user->email',
        'password' => 'wrong-password',
    ])
        ->assertSessionHasErrors('email');
});

it('mengijinkan users untuk logout', function () {
    $user = User::factory()->create();

    actingAs($user);

    post('/logout');

    assertGuest();
    $this->get('/')->assertOk();
});

it('menangani fungsi fitur ingat saya', function () {
    $user = User::factory()->create();

    $response = post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'remember' => 'on',
    ]);

    assertAuthenticated();
    $response->assertRedirect('/');

    $this->assertNotEmpty($response->headers->getCookies());
});

it('menampilkan validasi error pada form login', function () {
    post('/login', [
        'email' => '',
        'password' => '',
    ])
        ->assertSessionHasErrors(['email', 'password']);
});

it('dialihkan ke URL yang dituju setelah login ', function () {
    $user = User::factory()->create();

    withSession(['url.intended' => '/']);

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertRedirect('/');
});

it('menampilkan pesan validasi berhasil setelah sukses login', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
    ->assertSessionHas('toast_success', 'Selamat, anda berhasil masuk!');
});

it('alihkan kembali ke halaman login jika percobaan login gagal', function () {
    $user = User::factory()->create();
    post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])
        ->assertRedirect('/login');
});

it('mengijinkan login ketikan fitur remember me tidak dipilih', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    assertAuthenticated();
    $this->assertAuthenticatedAs($user);
});
