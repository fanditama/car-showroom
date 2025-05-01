<?php

use App\Livewire\Dashboard\UserManagement;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    // buat user sebagai admin
    $this->admin = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'role' => 'admin'
    ]);

    // Login as admin
    actingAs($this->admin);
});

test('dapat me-render user management component', function () {
    User::factory()->count(5)->create();

    Livewire::test(UserManagement::class)
        ->assertSuccessful()
        ->assertViewIs('livewire.dashboard.user-management');
});

test('user dapat di cari', function () {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);
    User::factory()->create(['name' => 'John Smith']);

    Livewire::test(UserManagement::class)
        ->set('search', 'John')
        ->assertSee('John Doe')
        ->assertSee('John Smith')
        ->assertDontSee('Jane Smith');
});

test('halaman pembuatan user dapat dibuka', function () {
    Livewire::test(UserManagement::class)
        ->call('create')
        ->assertSet('isOpen', true)
        ->assertSet('userId', null)
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('role', '');
});

test('user dapat dibuat', function () {
    Livewire::test(UserManagement::class)
        ->call('create')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('role', 'user')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('store')
        ->assertSet('isOpen', false);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'role' => 'user'
    ]);
});

test('halaman edit user dapat dibuka', function () {
    $user = User::factory()->create([
        'name' => 'Editable User',
        'email' => 'editable@example.com',
        'role' => 'user',
    ]);

    Livewire::test(UserManagement::class)
        ->call('edit', $user->id)
        ->assertSet('isOpen', true)
        ->assertSet('userId', $user->id)
        ->assertSet('name', 'Editable User')
        ->assertSet('email', 'editable@example.com')
        ->assertSet('role', 'user');
});

test('user dapat di-update', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'oldemail@example.com',
        'role' => 'user',
    ]);

    Livewire::test(UserManagement::class)
        ->call('edit', $user->id)
        ->set('name', 'Updated Name')
        ->set('email', 'newemail@example.com')
        ->set('role', 'admin')
        ->call('store')
        ->assertSet('isOpen', false);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'newemail@example.com',
        'role' => 'admin'
    ]);
});

test('user dapat di-update tanpa mengubah password', function () {
    $user = User::factory()->create([
        'name' => 'Keep Password',
        'email' => 'keep@example.com',
        'role' => 'user',
    ]);

    $originalPassword = $user->password;

    Livewire::test(UserManagement::class)
        ->call('edit', $user->id)
        ->set('name', 'New Name Only')
        ->set('email', 'keep@example.com')
        ->set('role', 'user')
        ->set('password', '')
        ->set('password_confirmation', '')
        ->call('store')
        ->assertSet('isOpen', false);

    $updatedUser = User::find($user->id);

    expect($updatedUser->name)->toBe('New Name Only');
    expect($updatedUser->password)->not()->toBe($originalPassword);
});

test('pesan notifikasi konfirmasi hapus dapat ditampilkan', function () {
    $user = User::factory()->create();

    Livewire::test(UserManagement::class)
        ->call('confirmDelete', $user->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('userToDelete', $user->id);
});

test('user dapat dihapus', function () {
    $user = User::factory()->create([
        'name' => 'Delete Me',
        'email' => 'delete@example.com',
    ]);

    Livewire::test(UserManagement::class)
        ->call('confirmDelete', $user->id)
        ->call('delete')
        ->assertSet('showDeleteModal', false);

    $this->assertDatabaseMissing('users', [
        'email' => 'delete@example.com'
    ]);
});

test('trigger validasi required saat membuat pengguna', function () {
    Livewire::test(UserManagement::class)
        ->call('create')
        ->set('name', '')
        ->set('email', '')
        ->set('role', '')
        ->set('password', '')
        ->set('password_confirmation', '')
        ->call('store')
        ->assertHasErrors(['name', 'email', 'role', 'password']);
});

test('trigger validasi format email', function () {
    Livewire::test(UserManagement::class)
        ->call('create')
        ->set('email', 'not-an-email')
        ->call('store')
        ->assertHasErrors(['email' => 'email']);
});

test('trigger validasi konfirmasi password', function () {
    Livewire::test(UserManagement::class)
        ->call('create')
        ->set('password', 'password123')
        ->set('password_confirmation', 'different-password')
        ->call('store')
        ->assertHasErrors(['password' => 'confirmed']);
});

test('trigger validasi email unik', function () {
    User::factory()->create([
        'email' => 'existing@example.com'
    ]);

    Livewire::test(UserManagement::class)
        ->call('create')
        ->set('name', 'Test User')
        ->set('email', 'existing@example.com')
        ->set('role', 'user')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('store')
        ->assertHasErrors(['email' => 'unique']);
});
