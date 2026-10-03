@props(['active' => false, 'clearAction' => 'clearFilters'])

<div class="mb-6 border border-[#2c2922] bg-[#151310] p-5">
    {{ $slot }}

    @if ($active)
        <button wire:click="{{ $clearAction }}"
            class="mt-4 text-[9px] uppercase tracking-[0.15em] text-[#857861] hover:text-[#c59b4a]">
            Clear filters
        </button>
    @endif
</div>