{{-- components/landing/archive.blade.php --}}
@props(['pillars'])

<section id="archive" class="scroll-mt-4 border-y border-line bg-ink-raised">
    <div class="mx-auto max-w-[1400px] px-6 py-24 lg:px-10 lg:py-32">
        <div class="mb-14 max-w-2xl">
            <x-landing.eyebrow class="mb-4">Within the Archive</x-landing.eyebrow>

            <x-landing.section-heading highlight="a record." class="text-4xl md:text-5xl">
                Everything has
            </x-landing.section-heading>

            <p class="mt-5 text-sm leading-7 text-muted">
                Ledger gives every corner of your fantasy world a place in history. Build the world, then preserve it.
            </p>
        </div>

        <div class="grid border-l border-t border-line-warm sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($pillars as $pillar)
                <x-landing.pillar-card :pillar="$pillar" />
            @endforeach
        </div>
    </div>
</section>