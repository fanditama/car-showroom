<?php

namespace Tests;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        // if want used more one panel
        Filament::setCurrentPanel(
            Filament::getPanel('admin'),
        );

        // set fileupload component to preserveFilenames for testing purpose
        FileUpload::configureUsing(function(FileUpload $component){
            $component->preserveFilenames();
        });
    }
}
