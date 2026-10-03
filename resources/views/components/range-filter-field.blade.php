@props(['minModel', 'maxModel', 'minValue', 'maxValue', 'label', 'min' => 0, 'max' => 100])

<div>
    <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">
        {{ $label }}: {{ $minValue }}–{{ $maxValue }}
    </label>
    <div class="flex items-center gap-2 pt-2">
        <input type="range" min="{{ $min }}" max="{{ $max }}" wire:model.live.debounce.300ms="{{ $minModel }}"
            class="w-full accent-[#806337]">
        <input type="range" min="{{ $min }}" max="{{ $max }}" wire:model.live.debounce.300ms="{{ $maxModel }}"
            class="w-full accent-[#806337]">
    </div>
</div>