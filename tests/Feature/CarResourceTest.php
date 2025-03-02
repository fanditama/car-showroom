<?php

use App\Filament\Resources\CarResource;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

it('can render car page', function () {
    $this->get(CarResource::getUrl('index'))->assertSuccessful();
});

it('can list cars', function () {
    $cars = Car::factory()->count(10)->create();

    Livewire(CarResource\Pages\ListCars::class)
        ->assertCanSeeTableRecords($cars);
});

it('can render create car page', function () {
    $this->get(CarResource::getUrl('create'))->assertSuccessful();
});

it('can create car', function () {

    $newData = Car::factory()->make();

    // generate random string file image
    $filename = Str::random().'.jpg';
    $file = UploadedFile::fake()->image($filename);

    livewire(CarResource\Pages\CreateCar::class)
        ->fillForm([
            'brand' => $newData->brand,
            'model' => $newData->model,
            'year' => $newData->year,
            'price' => $newData->price,
            'type' => $newData->type,
            'description' => $newData->description,
            'image_url' => $file,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Car::class, [
        'brand' => $newData->brand,
        'model' => $newData->model,
        'year' => $newData->year,
        'price' => $newData->price,
        'type' => $newData->type,
        'description' => $newData->description,
        'image_url' => 'cars/' . $filename,
    ]);
});

it('can validate input create car page', function () {

    // generate random string file image
    $filename = Str::random().'.jpg';
    $file = UploadedFile::fake()->image($filename);

    livewire(CarResource\Pages\CreateCar::class)
        ->fillForm([
            'brand' => null,
            'year' => null,
            'price' => null,
            'type' => null,
            'description' => 1234,
            'image_url' => $file,
        ])
        ->call('create')
        ->assertHasFormErrors(
            [
                    'brand' => 'required',
                    'year' => 'required',
                    'price' => 'required',
                    'type' => 'required',
                    'description' => 'string',
                ]
        );
});

it('can render edit car page', function () {
    $this->get(CarResource::getUrl('edit', [
        'record' => Car::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data edit car page', function () {
    $car = Car::factory()->create();

    livewire(CarResource\Pages\EditCar::class, [
        'record' => $car->getRouteKey(),
    ])
        ->assertFormSet([
            'car_id' => $car->car_id,
            'brand' => $car->brand,
            'year' => $car->year,
            'price' => $car->price,
            'type' => $car->type,
            'description' => $car->description,
        ]);
});

it('can save edit car page', function () {
    $car = Car::factory()->create();
    $newData = Car::factory()->make();

    // generate random string file image
    $filename = Str::random().'.jpg';
    $file = UploadedFile::fake()->image($filename);

    livewire(CarResource\Pages\EditCar::class, [
        'record' => $car->getRouteKey(),
    ])
        ->fillForm([
            'brand' => $newData->brand,
            'model' => $newData->model,
            'year' => $newData->year,
            'price' => $newData->price,
            'type' => $newData->type,
            'description' => $newData->description,
            'image_url' => $file,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($car->refresh())
        ->car_id->toBe($newData->car_id)
        ->model->toBe($newData->model)
        ->year->toBe($newData->year)
        ->price->toBe($newData->price)
        ->type->toBe($newData->type)
        ->description->toBe($newData->description)
        ->image_url->toBe('cars/' . $filename);
});

it('can validate input edit car page', function () {
    $car = Car::factory()->create();

    // generate random string file image
    $filename = Str::random().'.jpg';
    $file = UploadedFile::fake()->image($filename);

    livewire(CarResource\Pages\EditCar::class, [
        'record' => $car->getRouteKey(),
    ])
        ->fillForm([
            'brand' => null,
            'year' => null,
            'price' => null,
            'type' => null,
            'description' => 1234,
            'image_url' => $file,
        ])
        ->call('save')
        ->assertHasFormErrors(
          [
              'brand' => 'required',
              'year' => 'required',
              'price' => 'required',
              'type' => 'required',
              'description' => 'string',
          ]
        );
});

it('can delete car page', function () {
    $car = Car::factory()->create();

    livewire(CarResource\Pages\EditCar::class, [
        'record' => $car->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($car);
});
