<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::threat-reports.index')
        ->assertStatus(200);
});
