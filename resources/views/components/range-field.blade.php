@props(['model', 'label', 'min' => 0, 'max' => 100])

<div x-data="{ value: @entangle($model) }">
    <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">
        {{ $label }} — <span x-text="value"></span>
    </label>
    <input type="range" min="{{ $min }}" max="{{ $max }}" x-model="value" class="mt-3 w-full accent-[#806337]">
</div>