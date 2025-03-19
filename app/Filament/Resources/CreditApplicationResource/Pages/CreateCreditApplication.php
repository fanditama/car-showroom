<?php

namespace App\Filament\Resources\CreditApplicationResource\Pages;

use App\Filament\Resources\CreditApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCreditApplication extends CreateRecord
{
    protected static string $resource = CreditApplicationResource::class;

    protected static ?string $title = 'Buat Pengajuan Kredit';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
