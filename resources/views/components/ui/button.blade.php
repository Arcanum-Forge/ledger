@props(['target' => null, 'loading' => 'Working...'])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'flex w-full items-center justify-center gap-2 bg-bronze px-6 py-3.5 text-[9px] font-semibold uppercase tracking-[0.2em] text-parchment-bright transition hover:bg-bronze-light disabled:opacity-50']) }} wire:loading.attr="disabled" @if($target)
wire:target="{{ $target }}" @endif>
    <span wire:loading.remove @if($target) wire:target="{{ $target }}" @endif>{{ $slot }}</span>
    <span wire:loading @if($target) wire:target="{{ $target }}" @endif>{{ $loading }}</span>
</button>