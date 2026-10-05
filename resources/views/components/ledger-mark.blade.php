{{-- components/ledger-mark.blade.php --}}
@props(['size' => 'md']) {{-- sm | md --}}

@php
    $box = ['sm' => 'h-8 w-8', 'md' => 'h-9 w-9'][$size] ?? 'h-9 w-9';
    $icon = $size === 'sm' ? 'h-4 w-4' : 'h-[18px] w-[18px]';
@endphp

<div {{ $attributes->class(['flex items-center gap-3']) }}>
    <div @class([
        'flex items-center justify-center border border-mark-border bg-card',
        $box,
    ])>
        <x-tabler-crown @class(['text-gold-dim', $icon]) aria-hidden="true" />
    </div>

    <div>
        <div class="font-serif text-sm tracking-[0.16em] text-parchment-dim">LEDGER</div>
        <div class="text-[9px] uppercase tracking-[0.24em] text-faint">The Great Archive</div>
    </div>
</div>