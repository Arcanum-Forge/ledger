{{-- text-input.blade.php --}}
@props([
    'model',
    'label',
    'placeholder' => null,
    'type' => 'text',
    'variant' => 'form',
    'span' => false,
])

@php
    $classes = $variant === 'filter'
        ? 'w-full border border-line bg-card-inset px-3 py-2.5 text-xs text-parchment-bright outline-none placeholder:text-faint focus:border-bronze'
        : 'w-full border border-line bg-card-inset px-4 py-2.5 text-sm text-parchment-bright outline-none placeholder:text-faint focus:border-bronze';

    $wireAttr = $variant === 'filter' ? 'wire:model.live.debounce.400ms' : 'wire:model';
@endphp

<div @if ($span) class="col-span-full" @endif>
    <label class="mb-1.5 block text-[10px] uppercase tracking-[0.2em] text-gold-dim">{{ $label }}</label>
    <input type="{{ $type }}" {{ $wireAttr }}="{{ $model }}" @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge(['class' => $classes]) }}>

    @if ($variant === 'form')
        @error($model)
            <p class="mt-1.5 text-[11px] text-danger">{{ $message }}</p>
        @enderror
    @endif
</div>