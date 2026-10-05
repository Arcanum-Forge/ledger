{{-- detail-item.blade.php --}}
@props(['label'])

<div>
    <p class="text-[10px] uppercase tracking-[0.15em] text-faint">{{ $label }}</p>
    <p {{ $attributes->merge(['class' => 'mt-1 text-parchment-dim']) }}>{{ $slot }}</p>
</div>