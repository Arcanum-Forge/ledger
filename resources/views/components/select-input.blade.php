{{-- select-input.blade.php --}}
@props([
    'model',
    'label',
    'options',
    'variant' => 'form',
    'placeholder' => null,
])

@php
    $classes = $variant === 'filter'
        ? 'w-full border border-line bg-card-inset px-3 py-2.5 text-xs text-parchment-bright outline-none focus:border-bronze'
        : 'w-full border border-line bg-card-inset px-4 py-2.5 text-sm text-parchment-bright outline-none focus:border-bronze';

    $wireAttr = $variant === 'filter' ? 'wire:model.live' : 'wire:model';
@endphp

<div>
    <label class="mb-1.5 block text-[10px] uppercase tracking-[0.2em] text-gold-dim">{{ $label }}</label>
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
            <p class="mt-1.5 text-[11px] text-danger">{{ $message }}</p>
        @enderror
    @endif
</div>