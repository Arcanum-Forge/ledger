<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('kingdoms.index')
        ->assertStatus(200);
});
