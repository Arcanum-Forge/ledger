{{-- components/landing/button.blade.php --}}
@props([
    'href',
    'variant' => 'outline', // primary | outline
    'size' => 'md',         // sm | md | lg
    'icon' => null,         // arrow-right | arrow-up-right
])

@php
    $variants = [
        'primary' => 'bg-bronze text-parchment-bright hover:bg-bronze-light',
        'outline' => 'border border-bronze/70 bg-surface text-gold-soft hover:bg-surface-hover',
    ];

    $sizes = [
        'sm' => 'px-3 py-2 text-[10px] tracking-[0.15em] sm:px-4 sm:py-2.5 sm:tracking-[0.2em] md:px-5',
        'md' => 'px-6 py-3.5 text-[10px] tracking-[0.2em]',
        'lg' => 'px-7 py-4 text-[11px] tracking-[0.2em]',
    ];

    $paths = [
        'arrow-right' => 'M5 12h14M13 6l6 6-6 6',
        'arrow-up-right' => 'M5 19L19 5M8 5h11v11',
    ];

    $motion = $icon === 'arrow-up-right'
        ? 'group-hover:-translate-y-0.5 group-hover:translate-x-0.5'
        : 'group-hover:translate-x-1';
@endphp

<a href="{{ $href }}" {{ $attributes->class([
    'group inline-flex items-center gap-3 font-semibold uppercase transition',
    'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold',
    $variants[$variant],
    $sizes[$size],
]) }}>
    {{ $slot }}

    @if ($icon)
        <svg @class(['h-3.5 w-3.5 transition-transform', $motion]) fill="none" viewBox="0 0 24 24" stroke="currentColor"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $paths[$icon] }}" />
        </svg>
    @endif
</a>