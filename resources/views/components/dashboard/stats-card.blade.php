{{-- dashboard/stats-card.blade.php --}}
@props([
    'stats' => [],
])

<div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
    @foreach ($stats as $stat)
        <a href="{{ $stat['href'] }}" wire:navigate
            class="border border-line bg-card p-5 transition hover:border-bronze hover:bg-card-hover">
            <p class="text-[10px] uppercase tracking-[0.2em] text-gold-dim">
                {{ $stat['label'] }}
            </p>

            <p class="mt-2 font-serif text-3xl text-parchment">
                {{ $stat['count'] }}
            </p>
        </a>
    @endforeach
</div>