<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::rulers.index')
        ->assertStatus(200);
});
