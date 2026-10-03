@props([
    'model',
    'label',
    'options',
    'variant' => 'form', // 'form' | 'filter'
    'placeholder' => null, // e.g. 'Any' — only rendered as an option when given
])

@php
    $classes = $variant === 'filter'
        ? 'w-full border border-[#3b3225] bg-[#0d0c0a] px-3 py-2.5 text-xs text-[#e8dfca] outline-none focus:border-[#806337]'
        : 'w-full border border-[#3b3225] bg-[#0d0c0a] px-4 py-2.5 text-sm text-[#e8dfca] outline-none focus:border-[#806337]';

    $wireAttr = $variant === 'filter' ? 'wire:model.live' : 'wire:model';
@endphp

<div>
    <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">{{ $label }}</label>
    <select {{ $wireAttr }}="{{ $model }}" class="{{ $classes }}">
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>

    @if ($variant === 'form')
        @error($model)
            <p class="mt-1.5 text-[10px] text-[#c14545]">{{ $message }}</p>
        @enderror
    @endif
</div>