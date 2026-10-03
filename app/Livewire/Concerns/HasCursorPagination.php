<?php

namespace App\Livewire\Concerns;

trait HasCursorPagination
{
    public ?string $cursor = null;

    public function sortByColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->cursor = null;
    }

    public function goToCursor(?string $encoded): void
    {
        $this->cursor = $encoded;
    }
}