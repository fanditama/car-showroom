<?php

use App\Filament\Resources\TestDriveResource;
use App\Models\TestDrive;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

it('can render test drive page', function () {
    $this->get(TestDriveResource::getUrl('index'))->assertSuccessful();
});

it('can list test drives', function () {
    $test_drives = TestDrive::factory()->count(10)->create();

    livewire(TestDriveResource\Pages\ListTestDrives::class)
        ->assertCanSeeTableRecords($test_drives);
});

it('can render create test drive page', function () {
    $this->get(TestDriveResource::getUrl('create'))->assertSuccessful();
});

it('can create test drive', function () {
    $newData = TestDrive::factory()->make();

    livewire(TestDriveResource\Pages\CreateTestDrive::class)
        ->fillForm([
            'user_id' => $newData->user_id,
            'car_id' => $newData->car_id,
            'testdrive_date' => $newData->testdrive_date,
            'status' => $newData->status,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(TestDrive::class, [
        'user_id' => $newData->user_id,
        'car_id' => $newData->car_id,
        'testdrive_date' => $newData->testdrive_date,
        'status' => $newData->status,
    ]);
});

it('can validate input create test drive page', function () {

    livewire(TestDriveResource\Pages\CreateTestDrive::class)
        ->fillForm([
            'testdrive_date' => null,
            'status' => 'test',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'testdrive_date' => 'required',
            'status' => 'in:tertunda,disetujui,ditolak',
        ]);
});

it('can render edit test drive page', function () {
    $this->get(TestDriveResource::getUrl('edit', [
        'record' => TestDrive::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data edit test drive page', function () {
    $test_drive = TestDrive::factory()->create();

    livewire(TestDriveResource\Pages\EditTestDrive::class, [
        'record' => $test_drive->getRouteKey(),
    ])
        ->assertFormSet([
            'testdrive_id' => $test_drive->testdrive_id,
            'testdrive_date' => $test_drive->testdrive_date->toDateString(),
            'status' => $test_drive->status,
        ]);
});

it('can save edit test drive page', function () {
    $test_drive = TestDrive::factory()->create();
    $newData = TestDrive::factory()->make();

    livewire(TestDriveResource\Pages\EditTestDrive::class, [
        'record' => $test_drive->getRouteKey(),
    ])
        ->fillForm([
            'testdrive_id' => $newData->testdrive_id,
            'testdrive_date' => $newData->testdrive_date->toDateString(),
            'status' => $newData->status,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($test_drive->refresh())
        ->testdrive_id->toBe($newData->testdrive_id)
        ->testdrive_date->format('Y-m-d')->toBe($newData->testdrive_date->format('Y-m-d'))
        ->status->toBe($newData->status);
});

it('can validate input edit test drive page', function () {
    $test_drive = TestDrive::factory()->create();

    livewire(TestDriveResource\Pages\EditTestDrive::class, [
        'record' => $test_drive->getRouteKey(),
    ])
        ->fillForm([
            'testdrive_date' => null,
            'status' => 'test',
        ])
        ->call('save')
        ->assertHasFormErrors(
            [
                'testdrive_date' => 'required',
                'status' => 'in:tertunda,disetujui,ditolak',
            ]
        );
});

it('can delete test drive page', function () {
    $test_drive = TestDrive::factory()->create();

    livewire(TestDriveResource\Pages\EditTestDrive::class, [
        'record' => $test_drive->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($test_drive);
});