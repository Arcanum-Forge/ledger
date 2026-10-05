@props(['route', 'icon', 'label', 'badge' => null])

<a wire:navigate href="{{ $route === '#' ? '#' : route($route) }}" @if (request()->routeIs($route . '*'))
aria-current="page" @endif @class([
        'group flex items-center justify-between border px-3 py-2.5 transition',
        'border-mark-border bg-surface text-gold-soft' => request()->routeIs($route . '*'),
        'border-transparent text-muted hover:border-line hover:bg-card hover:text-parchment-dim' => !request()->routeIs($route . '*'),
    ])>
    <div class="flex items-center gap-3">
        <x-dynamic-component :component="$icon" class="h-4 w-4 shrink-0" />
        <span class="text-[11px] font-semibold uppercase tracking-[0.18em]">{{ $label }}</span>
    </div>

    @if ($badge)
        <span
            class="flex h-5 min-w-5 items-center justify-center border border-line-bronze bg-card px-1.5 text-[10px] text-bronze-light">
            {{ $badge }}
        </span>
    @endif
</a>