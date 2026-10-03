@props([
    'title',
    'href',
])

<div class="border border-[#2c2922] bg-[#151310]">
    <div class="flex items-center justify-between border-b border-[#2c2922] px-5 py-4">
        <p class="text-[9px] uppercase tracking-[0.25em] text-[#806337]">
            {{ $title }}
        </p>

        <a href="{{ $href }}" wire:navigate
            class="text-[9px] uppercase tracking-[0.15em] text-[#857861] hover:text-[#c59b4a]">
            View all →
        </a>
    </div>

    <div class="divide-y divide-[#2c2922]/60">
        {{ $slot }}
    </div>
</div>