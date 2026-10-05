{{-- components/landing/pillar-card.blade.php --}}
@props(['pillar'])

<article {{ $attributes->class([
    'group relative min-h-[280px] border-b border-r border-line-warm bg-card p-7',
    'transition-colors hover:bg-card-hover',
]) }}>
    <div aria-hidden="true"
        class="absolute right-6 top-6 font-serif text-3xl text-line-warm transition-colors group-hover:text-numeral-hover">
        {{ $pillar['number'] }}
    </div>

    <div aria-hidden="true"
        class="mb-10 flex h-10 w-10 items-center justify-center border border-line-bronze bg-card-inset text-gold-dim transition-colors group-hover:border-bronze group-hover:text-gold">
        <x-dynamic-component :component="'tabler-' . $pillar['symbol']" class="h-5 w-5" />
    </div>

    <p class="text-[10px] uppercase tracking-[0.22em] text-bronze">{{ $pillar['label'] }}</p>
    <h3 class="mt-2 font-serif text-2xl text-parchment-dim">{{ $pillar['title'] }}</h3>
    <p class="mt-4 max-w-sm text-xs leading-6 text-muted">{{ $pillar['description'] }}</p>

    <div aria-hidden="true"
        class="absolute bottom-0 left-0 h-px w-0 bg-bronze transition-all duration-500 group-hover:w-full motion-reduce:transition-none">
    </div>
</article>