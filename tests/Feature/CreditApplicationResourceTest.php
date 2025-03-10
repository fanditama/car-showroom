<?php

use App\Filament\Resources\CreditApplicationResource;
use App\Models\CreditApplication;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

it('can render credit application page', function () {
    $this->get(CreditApplicationResource::getUrl('index'))->assertSuccessful();
});

it('can list credit applications', function () {
    $credit_applications = CreditApplication::factory()->count(10)->create();

    livewire(CreditApplicationResource\Pages\ListCreditApplications::class)
        ->assertCanSeeTableRecords($credit_applications);
});

it('can render create credit applications page', function () {
    $this->get(CreditApplicationResource::getUrl('create'))->assertSuccessful();
});

it('can create credit applications', function() {
    $newData = CreditApplication::factory()->make();

    livewire(CreditApplicationResource\Pages\CreateCreditApplication::class)
        ->fillForm([
            'user_id' => $newData->user_id,
            'car_id' => $newData->car_id,
            'application_date' => $newData->application_date,
            'income' => $newData->income,
            'status' => $newData->status,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(CreditApplication::class, [
        'user_id' => $newData->user_id,
        'car_id' => $newData->car_id,
        'application_date' => $newData->application_date,
        'income' => $newData->income,
        'status' => $newData->status,
    ]);
});

it('can validate input create credit application page', function () {

    livewire(CreditApplicationResource\Pages\CreateCreditApplication::class)
        ->fillForm([
            'application_date' => 'test',
            'income' => null,
            'status' => 'test',
        ])
        ->call('create')
        ->assertHasFormErrors(
            [
                'application_date' => 'date',
                'income' => 'required',
                'status' => 'in:tertunda,disetujui,ditolak',
            ]
        );
});

it('can render edit credit application page', function () {
    $this->get(CreditApplicationResource::getUrl('edit', [
        'record' => CreditApplication::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data edit credit application page', function () {
    $credit_applications = CreditApplication::factory()->create();

    livewire(CreditApplicationResource\Pages\EditCreditApplication::class, [
        'record' => $credit_applications->getRouteKey(),
    ])
        ->assertFormSet([
            'creditapplication_id' => $credit_applications->creditapplication_id,
            'application_date' => $credit_applications->application_date->toDateTimeString(),
            'income' => $credit_applications->income,
            'status' => $credit_applications->status,
        ]);
});

it('can save edit credit application page', function () {
    $credit_applications = CreditApplication::factory()->create();
    $newData = CreditApplication::factory()->make();

    livewire(CreditApplicationResource\Pages\EditCreditApplication::class, [
        'record' => $credit_applications->getRouteKey(),
    ])
        ->fillForm([
            'creditapplication_id' => $newData->creditapplication_id,
            'application_date' => $newData->application_date->toDateTimeString(),
            'income' => $newData->income,
            'status' => $newData->status,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($credit_applications->refresh())
        ->creditapplication_id->toBe($newData->creditapplication_id)
        ->application_date->format('d/m/Y H:i:s')->toBe($newData->application_date->format('d/m/Y H:i:s'))
        ->income->toBe($newData->income)
        ->status->toBe($newData->status);
});

it('can validate input edit credit application page', function () {
    $credit_applications = CreditApplication::factory()->create();

    livewire(CreditApplicationResource\Pages\EditCreditApplication::class, [
        'record' => $credit_applications->getRouteKey(),
    ])
        ->fillForm([
            'application_date' => 'test',
            'income' => null,
            'status' => 'test',
        ])
        ->call('save')
        ->assertHasFormErrors(
            [
                'application_date' => 'date',
                'income' => 'required',
                'status' => 'in:tertunda,disetujui,ditolak',
            ]
        );
});

it('can delete credit application page', function () {
    $credit_application = CreditApplication::factory()->create();

    livewire(CreditApplicationResource\Pages\EditCreditApplication::class, [
        'record' => $credit_application->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($credit_application);
});