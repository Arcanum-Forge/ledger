{{-- multi-relation-picker.blade.php --}}
@props([
    'label',
    'searchModel',
    'placeholder',
    'options',
    'selected',
    'addAction',
    'removeAction',
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
            @forelse ($options as $option)
                @php
                    $optionId = data_get($option, $optionIdKey);
                    $optionLabel = data_get($option, $optionLabelKey);
                @endphp
                <button type="button" wire:key="opt-{{ $searchModel }}-{{ $optionId }}"
                    wire:click="{{ $addAction }}('{{ $optionId }}')"
                    class="block w-full px-3 py-2 text-left text-xs text-parchment-dim hover:bg-card-hover">
                    {{ $optionLabel }}
                </button>
            @empty
                <p class="px-3 py-2 text-xs text-faint">No matches</p>
            @endforelse
        </div>
    </div>

    @if ($selected->isNotEmpty())
        <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1">
            @foreach ($selected as $item)
                @php $itemId = data_get($item, $optionIdKey); @endphp
                <span wire:key="sel-{{ $searchModel }}-{{ $itemId }}" class="flex items-center gap-1.5">
                    <span class="text-[11px] text-gold-dim">{{ data_get($item, $optionLabelKey) }}</span>
                    <button type="button" wire:click="{{ $removeAction }}('{{ $itemId }}')" aria-label="Remove"
                        class="text-[11px] text-faint hover:text-danger">×</button>
                </span>
            @endforeach
        </div>
    @endif
</div>