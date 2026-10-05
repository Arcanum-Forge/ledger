{{-- empty-state.blade.php --}}
@props([
    'title',
    'hint',
    'filtered' => false,
    'filteredTitle' => null,
    'filteredHint' => null,
    'clearAction' => 'clearFilters',
])

@if ($filtered && $filteredTitle)
    <p class="font-serif text-sm text-muted">{{ $filteredTitle }}</p>
    <p class="mt-1 text-xs text-faint">
        <button wire:click="{{ $clearAction }}" class="underline hover:text-parchment-dim">Clear filters</button>
        {{ $filteredHint }}
    </p>
@else
    <p class="font-serif text-sm text-muted">{{ $title }}</p>
    <p class="mt-1 text-xs text-faint">{{ $hint }}</p>
@endif