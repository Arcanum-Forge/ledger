{{-- btn-primary.blade.php --}}
{{-- Any wire:click, type, wire:loading.attr etc. passed in just flow through via $attributes. --}}
<button {{ $attributes->merge(['class' => 'bg-bronze px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-ink transition hover:bg-bronze-light disabled:opacity-50']) }}>
    {{ $slot }}
</button>