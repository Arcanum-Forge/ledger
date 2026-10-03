<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::monsters.index')
        ->assertStatus(200);
});
