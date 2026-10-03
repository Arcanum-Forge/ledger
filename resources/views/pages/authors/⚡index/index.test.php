<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::authors.index')
        ->assertStatus(200);
});
