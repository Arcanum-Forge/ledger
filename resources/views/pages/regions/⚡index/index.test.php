<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::regions.index')
        ->assertStatus(200);
});
