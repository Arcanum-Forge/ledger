<button {{ $attributes->merge(['class' => 'bg-[#c14545] px-4 py-3 text-[9px] font-semibold uppercase tracking-[0.18em] text-[#eee5d1] transition hover:bg-[#d65656] disabled:opacity-50']) }}>
    {{ $slot }}
</button>