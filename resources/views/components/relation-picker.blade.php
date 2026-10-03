@props([
    'label',
    'searchModel',       // e.g. 'rulerSearch' or 'rulerFormSearch'
    'placeholder',
    'options',           // the *Results computed property — a collection of models or ['id' => ..., 'label' => ...] arrays
    'selectedId',        // the bound id property, e.g. $rulerFilter or $rulerId
    'selectedName',      // the bound display-name property, e.g. $rulerFilterName or $rulerName
    'selectAction',      // e.g. 'selectRulerFilter'
    'clearAction',       // e.g. 'clearRulerFilter'
    'clearLabel' => 'Any', // 'Any' for filters; 'Unclaimed' / 'Unknown' / 'Unconfirmed' etc. for forms
    'optionIdKey' => 'id',
    'optionLabelKey' => 'name', // 'name' for raw models (filter lists), 'label' for mapped arrays (form lists)
])

<div x-data="{ open: false }">
    <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">{{ $label }}</label>

    <div class="relative" @click.outside="open = false">
        <input type="text" wire:model.live.debounce.300ms="{{ $searchModel }}" @focus="open = true"
            placeholder="{{ $placeholder }}"
            class="w-full border border-[#3b3225] bg-[#0d0c0a] px-3 py-2.5 text-xs text-[#e8dfca] outline-none focus:border-[#806337]">

        <div x-show="open" x-cloak
            class="absolute z-20 mt-1 max-h-48 w-full overflow-y-auto border border-[#3b3225] bg-[#151310] shadow-lg">
            <button type="button" wire:click="{{ $clearAction }}" @click="open = false"
                class="block w-full px-3 py-2 text-left text-[9px] uppercase tracking-[0.15em] text-[#857861] hover:bg-[#1a1712]">
                {{ $clearLabel }}
            </button>

            @forelse ($options as $option)
                @php
                    $optionId = data_get($option, $optionIdKey);
                    $optionLabel = data_get($option, $optionLabelKey);
                @endphp
                <button type="button" wire:click="{{ $selectAction }}('{{ $optionId }}')" @click="open = false"
                    class="block w-full px-3 py-2 text-left text-xs {{ (string) $selectedId === (string) $optionId ? 'bg-[#806337]/20 text-[#c59b4a]' : 'text-[#c8b895]' }} hover:bg-[#1a1712]">
                    {{ $optionLabel }}
                </button>
            @empty
                <p class="px-3 py-2 text-xs text-[#625744]">No matches</p>
            @endforelse
        </div>
    </div>

    @if ($selectedName)
        <div class="mt-1.5 flex items-center gap-1.5">
            <span class="text-[10px] text-[#a17e43]">{{ $selectedName }}</span>
            <button type="button" wire:click="{{ $clearAction }}"
                class="text-[10px] text-[#625744] hover:text-[#c14545]">×</button>
        </div>
    @endif
</div>