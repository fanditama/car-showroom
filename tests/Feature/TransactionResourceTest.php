<?php

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

it('can render transaction page', function () {
    $this->get(TransactionResource::getUrl('index'))->assertSuccessful();
});

it('can list transactions', function () {
    $transactions = Transaction::factory()->count(10)->create();

    livewire(TransactionResource\Pages\ListTransactions::class)
        ->assertCanSeeTableRecords($transactions);
});

it('can render create transaction page', function () {
    $this->get(TransactionResource::getUrl('create'))->assertSuccessful();
});

it('can create transaction', function () {
    $newData = Transaction::factory()->make();

    livewire(TransactionResource\Pages\CreateTransaction::class)
        ->fillForm([
            'user_id' => $newData->user_id,
            'car_id' => $newData->car_id,
            'transaction_date' => $newData->transaction_date,
            'total_amount' => $newData->total_amount,
            'payment_method' => $newData->payment_method,
            'status' => $newData->status,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Transaction::class, [
        'user_id' => $newData->user_id,
        'car_id' => $newData->car_id,
        'transaction_date' => $newData->transaction_date,
        'total_amount' => $newData->total_amount,
        'payment_method' => $newData->payment_method,
        'status' => $newData->status,
    ]);
});

it('can validate input create transaction page', function () {

    livewire(TransactionResource\Pages\CreateTransaction::class)
        ->fillForm([
            'transaction_date' => 'test',
            'total_amount' => null,
            'payment_method' => 'test',
            'status' => 'test',
        ])
        ->call('create')
        ->assertHasFormErrors(
            [
                'transaction_date' => 'date',
                'total_amount' => 'required',
                'payment_method' => 'in:cash,credit_card,debit_card',
                'status' => 'in:pending,success,cancelled',
            ]
        );
});

it('can render edit transaction page', function () {
    $this->get(TransactionResource::getUrl('edit', [
        'record' => Transaction::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data edit transaction page', function () {
    $transaction = Transaction::factory()->create();

    livewire(TransactionResource\Pages\EditTransaction::class, [
        'record' => $transaction->getRouteKey(),
    ])
        ->assertFormSet([
            'transaction_id' => $transaction->transaction_id,
            'transaction_date' => $transaction->transaction_date->toDateTimeString(),
            'total_amount' => $transaction->total_amount,
            'payment_method' => $transaction->payment_method,
            'status' => $transaction->status,
        ]);
});

it('can save edit transaction page', function () {
    $transaction = Transaction::factory()->create();
    $newData = Transaction::factory()->make();

    livewire(TransactionResource\Pages\EditTransaction::class, [
        'record' => $transaction->getRouteKey(),
    ])
        ->fillForm([
            'transaction_date' => $newData->transaction_date->toDateTimeString(),
            'total_amount' => $newData->total_amount,
            'payment_method' => $newData->payment_method,
            'status' => $newData->status,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($transaction->refresh())
        ->transaction_date->format('d/m/Y H:i:s')->toBe($newData->transaction_date->format('d/m/Y H:i:s'))
        ->total_amount->toBe($newData->total_amount)
        ->payment_method->toBe($newData->payment_method)
        ->status->toBe($newData->status);
});

it('can validate input edit transaction page', function () {
    $transaction = Transaction::factory()->create();

    livewire(TransactionResource\Pages\EditTransaction::class, [
        'record' => $transaction->getRouteKey(),
    ])
        ->fillForm([
            'transaction_date' => 'test',
            'total_amount' => null,
            'payment_method' => 'test',
            'status' => 'test',
        ])
        ->call('save')
        ->assertHasFormErrors(
            [
                'transaction_date' => 'date',
                'total_amount' => 'required',
                'payment_method' => 'in:cash,credit_card,debit_card',
                'status' => 'in:pending,success,cancelled',
            ]
        );
});

it('can delete transaction page', function () {
    $transaction = Transaction::factory()->create();

    livewire(TransactionResource\Pages\EditTransaction::class, [
        'record' => $transaction->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($transaction);
});