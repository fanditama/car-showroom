<?php

namespace App\Filament\Resources\TestDriveResource\Pages;

use App\Filament\Resources\TestDriveResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestDrive extends CreateRecord
{
    protected static string $resource = TestDriveResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
