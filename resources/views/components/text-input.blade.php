@props([
    'model',
    'label',
    'placeholder' => null,
    'type' => 'text',
    'variant' => 'form', // 'form' | 'filter'
    'span' => false,     // col-span-full — used for a full-width Search field in a filter grid
])

@php
    $classes = $variant === 'filter'
        ? 'w-full border border-[#3b3225] bg-[#0d0c0a] px-3 py-2.5 text-xs text-[#e8dfca] outline-none focus:border-[#806337]'
        : 'w-full border border-[#3b3225] bg-[#0d0c0a] px-4 py-2.5 text-sm text-[#e8dfca] outline-none focus:border-[#806337]';

    $wireAttr = $variant === 'filter' ? 'wire:model.live.debounce.400ms' : 'wire:model';
@endphp

<div @if ($span) class="col-span-full" @endif>
    <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">{{ $label }}</label>
    <input type="{{ $type }}" {{ $wireAttr }}="{{ $model }}" @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge(['class' => $classes]) }}>

    @if ($variant === 'form')
        @error($model)
            <p class="mt-1.5 text-[10px] text-[#c14545]">{{ $message }}</p>
        @enderror
    @endif
</div>