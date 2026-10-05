{{-- components/landing/cta.blade.php --}}
<section class="border-b border-line">
    <div class="mx-auto max-w-[1000px] px-6 py-28 text-center lg:py-36">
        <x-landing.eyebrow tone="bronze" :rule="false" class="mb-5 justify-center">
            Your world awaits
        </x-landing.eyebrow>

        <x-landing.section-heading highlight="archive." class="text-4xl md:text-6xl">
            Begin your
        </x-landing.section-heading>

        <p class="mx-auto mt-6 max-w-lg text-sm leading-7 text-muted">
            Give your world a place to remember itself.
        </p>

        <x-landing.auth-button guest="Enter the Archive" member="Enter the Archive" size="lg" icon="arrow-up-right"
            class="mt-9" />
    </div>
</section>