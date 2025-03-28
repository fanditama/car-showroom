<?php

use App\Livewire\Content;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Content::class)
        ->assertStatus(200);
});
