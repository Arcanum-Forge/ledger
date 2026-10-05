{{-- components/landing/eyebrow.blade.php --}}
@props(['tone' => 'dim', 'rule' => true])

@php
    $color = $tone === 'dim' ? 'text-gold-dim' : 'text-bronze';
@endphp

<div {{ $attributes->class(['flex items-center gap-3']) }}>
    @if ($rule)
        <span class="h-px w-8 bg-bronze" aria-hidden="true"></span>
    @endif
    <span @class(['text-[10px] uppercase tracking-[0.28em]', $color])>{{ $slot }}</span>
</div>