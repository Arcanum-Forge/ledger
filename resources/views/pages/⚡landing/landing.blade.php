{{-- ⚡landing/landing.blade.php --}}
<div class="min-h-screen bg-ink text-parchment selection:bg-bronze/30 selection:text-parchment-bright">
    <x-landing.header />

    <main>
        <x-landing.hero />
        <x-landing.archive :pillars="$this->pillars" />
    </main>

    <x-landing.footer />
</div>