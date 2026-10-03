<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::auth.login')
        ->assertStatus(200);
});
