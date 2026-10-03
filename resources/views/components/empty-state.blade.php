@props([
    'title',
    'hint',
    'filtered' => false,
    'filteredTitle' => null,
    'filteredHint' => null,
    'clearAction' => 'clearFilters',
])

@if ($filtered && $filteredTitle)
    <p class="font-serif text-sm text-[#8f826b]">{{ $filteredTitle }}</p>
    <p class="mt-1 text-xs text-[#625744]">
        <button wire:click="{{ $clearAction }}" class="underline hover:text-[#c8b895]">Clear filters</button>
        {{ $filteredHint }}
    </p>
@else
    <p class="font-serif text-sm text-[#8f826b]">{{ $title }}</p>
    <p class="mt-1 text-xs text-[#625744]">{{ $hint }}</p>
@endif