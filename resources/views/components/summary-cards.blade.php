{{-- summary-cards.blade.php --}}
<div {{ $attributes->merge(['class' => 'mb-8 grid grid-cols-2 border border-line bg-card sm:grid-cols-3']) }}>
    {{ $slot }}
</div>