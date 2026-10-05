<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('theme-switcher')
        ->assertStatus(200);
});
