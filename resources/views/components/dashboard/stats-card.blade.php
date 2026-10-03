@props([
    'stats' => [],
])

<div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
    @foreach ($stats as $stat)
        <a href="{{ $stat['href'] }}" wire:navigate
            class="border border-[#2c2922] bg-[#151310] p-5 transition hover:border-[#806337]/40 hover:bg-[#191611]">
            <p class="text-[9px] uppercase tracking-[0.2em] text-[#806337]">
                {{ $stat['label'] }}
            </p>

            <p class="mt-2 font-serif text-3xl text-[#d8c8a8]">
                {{ $stat['count'] }}
            </p>
        </a>
    @endforeach
</div>