<?php

namespace App\Filament\Resources\TestDriveResource\Pages;

use App\Filament\Resources\TestDriveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestDrive extends EditRecord
{
    protected static string $resource = TestDriveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
