{{-- btn-secondary.blade.php --}}
<button {{ $attributes->merge(['class' => 'border border-line px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-muted transition hover:border-bronze hover:text-parchment-dim']) }}>
    {{ $slot }}
</button>