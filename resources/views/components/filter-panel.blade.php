{{-- filter-panel.blade.php --}}
@props(['active' => false, 'clearAction' => 'clearFilters'])

<div class="mb-6 border border-line bg-card p-5">
    {{ $slot }}

    @if ($active)
        <button wire:click="{{ $clearAction }}"
            class="mt-4 text-[10px] uppercase tracking-[0.15em] text-muted hover:text-gold">
            Clear filters
        </button>
    @endif
</div>