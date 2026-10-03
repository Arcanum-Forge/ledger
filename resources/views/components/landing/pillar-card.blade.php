@props(['pillar'])

<article
    class="group relative min-h-[280px] border-b border-r border-[#302a20] bg-[#151310] p-7 transition-colors hover:bg-[#191611]">
    <div class="absolute right-6 top-6 font-serif text-3xl text-[#302a20] transition-colors group-hover:text-[#403421]">
        {{ $pillar['number'] }}
    </div>

    <div
        class="mb-10 flex h-10 w-10 items-center justify-center border border-[#3b3225] bg-[#12110f] text-sm text-[#a17e43] transition-colors group-hover:border-[#806337] group-hover:text-[#c59b4a]">
        <x-dynamic-component :component="'tabler-' . $pillar['symbol']" />
    </div>

    <div class="text-[9px] uppercase tracking-[0.22em] text-[#806337]">{{ $pillar['label'] }}</div>
    <h3 class="mt-2 font-serif text-2xl text-[#ddd2bb]">{{ $pillar['title'] }}</h3>
    <p class="mt-4 max-w-sm text-xs leading-6 text-[#756d5e]">{{ $pillar['description'] }}</p>

    <div class="absolute bottom-0 left-0 h-px w-0 bg-[#806337] transition-all duration-500 group-hover:w-full"></div>
</article>