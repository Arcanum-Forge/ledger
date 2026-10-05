{{-- relation-picker.blade.php --}}
@props([
    'label',
    'searchModel',
    'placeholder',
    'options',
    'selectedId',
    'selectedName',
    'selectAction',
    'clearAction',
    'clearLabel' => 'Any',
    'optionIdKey' => 'id',
    'optionLabelKey' => 'name',
])

<div x-data="{ open: false }">
    <label class="mb-1.5 block text-[10px] uppercase tracking-[0.2em] text-gold-dim">{{ $label }}</label>

    <div class="relative" @click.outside="open = false">
        <input type="text" wire:model.live.debounce.300ms="{{ $searchModel }}" @focus="open = true"
            placeholder="{{ $placeholder }}"
            class="w-full border border-line bg-card-inset px-3 py-2.5 text-xs text-parchment-bright outline-none placeholder:text-faint focus:border-bronze">

        <div x-show="open" x-cloak
            class="absolute z-20 mt-1 max-h-48 w-full overflow-y-auto border border-line bg-card shadow-lg">
            <button type="button" wire:click="{{ $clearAction }}" @click="open = false"
                class="block w-full px-3 py-2 text-left text-[10px] uppercase tracking-[0.15em] text-muted hover:bg-card-hover">
                {{ $clearLabel }}
            </button>

            @forelse ($options as $option)
                @php
                    $optionId = data_get($option, $optionIdKey);
                    $optionLabel = data_get($option, $optionLabelKey);
                @endphp
                <button type="button" wire:click="{{ $selectAction }}('{{ $optionId }}')" @click="open = false"
                    class="block w-full px-3 py-2 text-left text-xs hover:bg-card-hover {{ (string) $selectedId === (string) $optionId ? 'bg-surface text-gold' : 'text-parchment-dim' }}">
                    {{ $optionLabel }}
                </button>
            @empty
                <p class="px-3 py-2 text-xs text-faint">No matches</p>
            @endforelse
        </div>
    </div>

    @if ($selectedName)
        <div class="mt-1.5 flex items-center gap-1.5">
            <span class="text-[11px] text-gold-dim">{{ $selectedName }}</span>
            <button type="button" wire:click="{{ $clearAction }}" aria-label="Clear"
                class="text-[11px] text-faint hover:text-danger">×</button>
        </div>
    @endif
</div>