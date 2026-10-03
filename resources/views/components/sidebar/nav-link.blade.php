@props(['route', 'icon', 'label', 'badge' => null])

<a wire:navigate href="{{ $route === '#' ? '#' : route($route) }}" @class([
    'group flex items-center justify-between border px-3 py-2.5 transition',
    'border-[#806337]/40 bg-[#241f16] text-[#d2b26e]' => request()->routeIs($route . '*'),
    'border-transparent text-[#857861] hover:border-[#806337]/20 hover:bg-[#211c14] hover:text-[#c8b895]' => !request()->routeIs($route . '*'),
])>
    <div class="flex items-center gap-3">
        <x-dynamic-component :component="$icon" class="h-4 w-4 shrink-0" />
        <span class="text-[9px] font-semibold uppercase tracking-[0.18em]">{{ $label }}</span>
    </div>

    @if ($badge)
        <span
            class="flex h-5 min-w-5 items-center justify-center border border-[#806337]/40 bg-[#211c14] px-1.5 text-[8px] text-[#b98967]">
            {{ $badge }}
        </span>
    @endif
</a>