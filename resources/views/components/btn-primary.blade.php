{{-- Any wire:click, type, wire:loading.attr etc. passed in just flow through via $attributes. --}}
<button {{ $attributes->merge(['class' => 'bg-[#806337] px-5 py-3 text-[9px] font-semibold uppercase tracking-[0.2em] text-[#eee5d1] transition hover:bg-[#967744] disabled:opacity-50']) }}>
    {{ $slot }}
</button>