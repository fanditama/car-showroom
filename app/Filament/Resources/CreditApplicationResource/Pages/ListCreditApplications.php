<?php

namespace App\Filament\Resources\CreditApplicationResource\Pages;

use App\Filament\Resources\CreditApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreditApplications extends ListRecords
{
    protected static string $resource = CreditApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
