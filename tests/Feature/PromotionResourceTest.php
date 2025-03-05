<?php

use App\Filament\Resources\PromotionResource;
use App\Models\Promotion;
use Filament\Actions\DeleteAction;
use function Pest\Livewire\livewire;

it('can render promotion page', function () {
    $this->get(PromotionResource::getUrl('index'))->assertSuccessful();
});

it('can list promotions', function () {
    $promotions = Promotion::factory()->count(10)->create();

    livewire(PromotionResource\Pages\ListPromotions::class)
        ->assertCanSeeTableRecords($promotions);
});

it('can render create promotion page', function () {
    $this->get(PromotionResource::getUrl('create'))->assertSuccessful();
});

it('can create promotion', function () {
    $newData = Promotion::factory()->make();

    livewire(PromotionResource\Pages\CreatePromotion::class)
        ->fillForm([
            'car_id' => $newData->car_id,
            'title' => $newData->title,
            'description' => $newData->description,
            'discount' => $newData->discount,
            'start_date' => $newData->start_date,
            'end_date' => $newData->end_date,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Promotion::class, [
        'car_id' => $newData->car_id,
        'title' => $newData->title,
        'description' => $newData->description,
        'discount' => $newData->discount,
        'start_date' => $newData->start_date,
        'end_date' => $newData->end_date,
    ]);
});

it('can validate input create promotion page', function () {

    livewire(PromotionResource\Pages\CreatePromotion::class)
        ->fillForm([
            'title' => null,
            'description' => 1234,
            'discount' => 'test',
            'start_date' => 'test',
            'end_date' => 'test',
        ])
        ->call('create')
        ->assertHasFormErrors(
            [
                'title' => 'required',
                'description' => 'string',
                'discount' => 'numeric',
                'start_date' => 'date',
                'end_date' => 'date',
            ]
        );
});

it('can render edit promotion page', function () {
    $this->get(PromotionResource::getUrl('edit', [
        'record' => Promotion::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data edit promotion page', function () {
    $promotion = Promotion::factory()->create();

    livewire(PromotionResource\Pages\EditPromotion::class, [
        'record' => $promotion->getRouteKey(),
    ])
        ->assertFormSet([
            'promotion_id' => $promotion->promotion_id,
            'title' => $promotion->title,
            'description' => $promotion->description,
            'discount' => $promotion->discount,
            'start_date' => $promotion->start_date->toDateString(),
            'end_date' => $promotion->end_date->toDateString(),
        ]);
});

it('can save edit promotion page', function () {
    $promotion = Promotion::factory()->create();
    $newData = Promotion::factory()->make();

    livewire(PromotionResource\Pages\EditPromotion::class, [
        'record' => $promotion->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newData->title,
            'description' => $newData->description,
            'discount' => $newData->discount,
            'start_date' => $newData->start_date->toDateString(),
            'end_date' => $newData->end_date->toDateString(),
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($promotion->refresh())
        ->promotion_id->toBe($newData->promotion_id)
        ->title->toBe($newData->title)
        ->description->toBe($newData->description)
        ->discount->toBe($newData->discount)
        ->start_date->format('Y-m-d')->toBe($newData->start_date->format('Y-m-d'))
        ->end_date->format('Y-m-d')->toBe($newData->end_date->format('Y-m-d'));
});

it('can validate input edit promotion page', function () {
    $promotion = Promotion::factory()->create();

    livewire(PromotionResource\Pages\EditPromotion::class, [
        'record' => $promotion->getRouteKey(),
    ])
        ->fillForm([
            'title' => null,
            'description' => 1234,
            'discount' => 'test',
            'start_date' => 'test',
            'end_date' => 'test',
        ])
        ->call('save')
        ->assertHasFormErrors(
            [
                'title' => 'required',
                'description' => 'string',
                'discount' => 'numeric',
                'start_date' => 'date',
                'end_date' => 'date',
            ]
        );
});

it('can delete promotion page', function () {
    $promotion = Promotion::factory()->create();

    livewire(PromotionResource\Pages\EditPromotion::class, [
        'record' => $promotion->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($promotion);
});