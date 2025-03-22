<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;

beforeEach(function () {
    Auth::logout();
});

test('arahkan halaman ke google provider', function () {
    $response = $this->get(route('socialite.redirect', ['provider' => 'google']));
    $response->assertRedirect();
});

test('arahkan halaman ke facebook provider', function () {
    $response = $this->get(route('socialite.redirect', ['provider' => 'facebook']));
    $response->assertRedirect();
});

test('tolak provider yang tidak valid', function () {
    $response = $this->get(route('socialite.redirect', ['provider' => 'invalid']));
    $response->assertSessionHasErrors('provider');
});

test('bisa login dengan google', function () {
    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('123456789');
    $socialiteUser->shouldReceive('getName')->andReturn('Test User');
    $socialiteUser->shouldReceive('getEmail')->andReturn('test@example.com');

    Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

    $response = $this->get(route('socialite.callback', ['provider' => 'google']));

    $this->assertAuthenticated();
    $response->assertRedirect('/');

    $user = User::where('email', 'test@example.com')->first();
    $this->assertNotNull($user);
    $this->assertEquals('123456789', $user->google_id);
});

test('bisa login dengan facebook', function () {
    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('987654321');
    $socialiteUser->shouldReceive('getName')->andReturn('Test User');
    $socialiteUser->shouldReceive('getEmail')->andReturn('test@example.com');

    Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

    $response = $this->get(route('socialite.callback', ['provider' => 'facebook']));

    $this->assertAuthenticated();
    $response->assertRedirect('/');

    $user = User::where('email', 'test@example.com')->first();
    $this->assertNotNull($user);
    $this->assertEquals('987654321', $user->facebook_id);
});

test('pengguna yang sudah ada dapat masuk dengan provider yang dipilih', function () {
    // buat user yang sudah ada
    $existingUser = User::factory()->create([
        'email' => 'existing@example.com',
        'google_id' => null
    ]);

    // Mock Socialite
    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('123456789');
    $socialiteUser->shouldReceive('getName')->andReturn($existingUser->name);
    $socialiteUser->shouldReceive('getEmail')->andReturn($existingUser->email);

    Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

    $response = $this->get(route('socialite.callback', ['provider' => 'google']));

    $this->assertAuthenticated();
    $response->assertRedirect('/');

    // Cek apakah user sudah memiliki google_id
    $existingUser->refresh();
    $this->assertEquals('123456789', $existingUser->google_id);
});
