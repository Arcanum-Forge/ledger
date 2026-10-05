{{-- dashboard/panel.blade.php --}}
@props([
    'title',
    'href',
])

<div class="border border-line bg-card">
    <div class="flex items-center justify-between border-b border-line px-5 py-4">
        <p class="text-[10px] uppercase tracking-[0.25em] text-gold-dim">
            {{ $title }}
        </p>

        <a href="{{ $href }}" wire:navigate
            class="text-[10px] uppercase tracking-[0.15em] text-muted transition hover:text-gold">
            View all →
        </a>
    </div>

    <div class="divide-y divide-line/60">
        {{ $slot }}
    </div>
</div>