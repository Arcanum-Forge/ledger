<?php

namespace App\Livewire\Concerns;

trait HasModalCrud
{
    public bool $showModal = false;
    public string $modalMode = 'create'; // create | edit | view | delete

    public function openCreate(): void
    {
        $this->resetForm();

        $this->modalMode = 'create';
        $this->showModal = true;
    }

    public function switchToEdit(): void
    {
        $this->modalMode = 'edit';
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalMode = 'create';

        $this->resetForm();
    }
}