<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::users.index')
        ->assertStatus(200);
});
