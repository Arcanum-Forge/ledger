<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::leaders.index')
        ->assertStatus(200);
});
