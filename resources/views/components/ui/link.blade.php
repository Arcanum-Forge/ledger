@props(['variant' => 'gold'])

@php
    $color = match ($variant) {
        'faint' => 'text-faint',
        default => 'text-gold-dim',
    };
@endphp

<a {{ $attributes->class(["text-[9px] uppercase tracking-[0.15em] transition hover:text-gold", $color]) }}>
    {{ $slot }}
</a>