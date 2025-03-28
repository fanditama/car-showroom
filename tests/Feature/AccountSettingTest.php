<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;

it('dapat mengunjungi halaman account settings', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = $this->get(route('account.settings'));

    $response->assertStatus(200);
    $response->assertViewIs('account.settings');
});

it('dapat mengubah informasi profil', function () {
    $user = User::factory()->create();
    actingAs($user);

    $newName = fake()->name;
    $newEmail = fake()->unique()->safeEmail;

    $response = $this->put(route('account.profile.update'), [
        'name' => $newName,
        'email' => $newEmail,
    ]);

    $response->assertRedirect(route('account.settings'));
    $response->assertSessionHas('toast_success', 'Profil berhasil diperbarui!');

    $user->refresh();
    expect($user->name)->toBe($newName);
    expect($user->email)->toBe($newEmail);
});

it('tidak dapat mengubah profile dengan data yang invalid', function () {
    $user = User::factory()->create();
    actingAs($user);

    $invalidEmail = 'invalid-email';

    $response = $this->put(route('account.profile.update'), [
        'name' => '',
        'email' => $invalidEmail,
    ]);

    $response->assertSessionHasErrors(['name', 'email']);

    $user->refresh();
    expect($user->name)->not->toBe('');
    expect($user->email)->not->toBe($invalidEmail);
});

it('tidak dapat mengubah profil dengan email yang sudah ada', function () {
    $user = User::factory()->create();
    $existingUser = User::factory()->create();

    actingAs($user);

    $response = $this->put(route('account.profile.update'), [
        'name' => fake()->name,
        'email' => $existingUser->email,
    ]);

    $response->assertSessionHasErrors('email');

    $user->refresh();
    expect($user->email)->not->toBe($existingUser->email);
});

it('dapat mengubah password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('current_password'),
    ]);
    actingAs($user);

    $newPassword = 'new_password';

    $response = $this->put(route('account.password.update'), [
        'current_password' => 'current_password',
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ]);

    $response->assertRedirect(route('account.settings'));
    $response->assertSessionHas('toast_success', 'Password berhasil diperbarui!');

    $user->refresh();
    expect(Hash::check($newPassword, $user->password))->toBeTrue();
});

it('tidak dapat mengubah password dengan password saat ini yang salah', function () {
    $user = User::factory()->create([
        'password' => Hash::make('current_password'),
    ]);
    actingAs($user);

    $newPassword = 'new_password';

    $response = $this->put(route('account.password.update'), [
        'current_password' => 'incorrect_password',
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ]);

    $response->assertSessionHasErrors('current_password');
    $user->refresh();
    expect(Hash::check($newPassword, $user->password))->toBeFalse();
});

it('tidak dapat mengubah password dengan password yang tidak dikonfirmasi', function () {
    $user = User::factory()->create([
        'password' => Hash::make('current_password'),
    ]);
    actingAs($user);

    $newPassword = 'new_password';

    $response = $this->put(route('account.password.update'), [
        'current_password' => 'current_password',
        'password' => $newPassword,
        'password_confirmation' => 'different_password',
    ]);

    $response->assertSessionHasErrors('password');

    $user->refresh();
    expect(Hash::check($newPassword, $user->password))->toBeFalse();
});
