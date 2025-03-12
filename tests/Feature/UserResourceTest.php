<?php

use App\Filament\Resources\UserResource;
use App\Models\User;

use Filament\Actions\DeleteAction;
use function Pest\Livewire\livewire;

it('can render user page', function () {
    $this->get(UserResource::getUrl('index'))->assertSuccessful();
});

it('can render create user page', function () {
    $this->get(UserResource::getUrl('create'))->assertSuccessful();
});

it('can create user', function () {
    $newData = User::factory()->make();

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
            'password' => $newData->password,
            'address' => $newData->address,
            'phone_number' => $newData->phone_number,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $newData->name,
        'email' => $newData->email,
        'password' => $newData->password,
        'address' => $newData->address,
        'phone_number' => $newData->phone_number,
    ]);
});

it('can validate input create user page', function () {

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => null,
            'password' => null,
            'address' => 1234,
        ])
        ->call('create')
        ->assertHasFormErrors(
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'address' => 'string',
            ]
        );
});

it('can render edit user page', function () {
    $this->get(UserResource::getUrl('edit', [
        'record' => User::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data user page', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->assertFormSet([
            'user_id' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
        ]);
});

it('can save edit user page', function () {
    $user = User::factory()->create();
    $newData = User::factory()->make();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'user_id' => $newData->user_id,
            'name' => $newData->name,
            'email' => $newData->email,
            'password' => $newData->password,
            'address' => $newData->address,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh())
        ->name->toBe($newData->name)
        ->email->toBe($newData->email)
        ->password->toBe($newData->password)
        ->address->toBe($newData->address);
});

it('can validate input edit user page', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'name' => null,
            'email' => null,
            'password' => null,
            'address' => 1234,
        ])
        ->call('save')
        ->assertHasFormErrors(
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'address' => 'string',
            ]
        );
});

it('can delete user page', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($user);
});