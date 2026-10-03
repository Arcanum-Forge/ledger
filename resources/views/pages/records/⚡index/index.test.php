<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::records.index')
        ->assertStatus(200);
});
