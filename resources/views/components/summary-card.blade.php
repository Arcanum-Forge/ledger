{{-- summary-card.blade.php --}}
@props(['label', 'last' => false])

<div class="{{ $last ? 'p-5' : 'border-b border-r border-line p-5 sm:border-b-0' }}">
    <p class="text-[10px] uppercase tracking-[0.25em] text-gold-dim">{{ $label }}</p>
    <div class="mt-2">{{ $slot }}</div>
</div>