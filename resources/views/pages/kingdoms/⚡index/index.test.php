<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::kingdoms.index')
        ->assertStatus(200);
});
