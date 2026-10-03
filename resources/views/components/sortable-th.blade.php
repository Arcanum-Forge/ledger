@props(['column', 'label', 'sortBy', 'sortDirection'])

<th class="whitespace-nowrap px-5 py-4">
    <button wire:click="sortByColumn('{{ $column }}')"
        class="flex items-center gap-1.5 transition hover:text-[#a17e43]">
        {{ $label }}

        @if ($sortBy === $column)
            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
        @endif
    </button>
</th>