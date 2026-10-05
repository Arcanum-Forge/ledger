{{-- btn-danger.blade.php --}}
<button {{ $attributes->merge(['class' => 'bg-danger px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-ink transition hover:brightness-110 disabled:opacity-50']) }}>
    {{ $slot }}
</button>