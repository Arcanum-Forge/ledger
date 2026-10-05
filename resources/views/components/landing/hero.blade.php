{{-- components/landing/hero.blade.php --}}
<section id="hero" class="relative overflow-hidden">
    <x-landing.grid-backdrop />

    <div aria-hidden="true"
        class="pointer-events-none absolute left-1/2 top-1/2 h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-bronze/[0.035] blur-3xl">
    </div>

    <div class="relative mx-auto max-w-[1400px] px-6 py-28 lg:px-10 lg:py-40">
        <div class="max-w-5xl">
            <x-landing.eyebrow class="mb-6">The Great Archive</x-landing.eyebrow>

            <h1 class="font-serif text-6xl leading-[0.9] tracking-[-0.035em] sm:text-7xl md:text-8xl lg:text-[9rem]">
                The world's
                <span class="block text-gold">living record.</span>
            </h1>

            <p class="mt-10 max-w-2xl text-sm leading-8 text-muted md:text-base">
                A centralized archive for the kingdoms, factions, histories, creatures, and threats that define your
                world.
            </p>

            <div class="mt-9 flex flex-wrap items-center gap-4">
                <x-landing.button href="#archive" variant="primary" icon="arrow-right">
                    Explore the Archive
                </x-landing.button>
            </div>
        </div>
    </div>
</section>