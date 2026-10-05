{{-- components/landing/footer.blade.php --}}
<footer class="bg-ink">
    <x-landing.cta />

    <div
        class="mx-auto flex max-w-[1400px] flex-col gap-6 px-6 py-8 md:flex-row md:items-center md:justify-between lg:px-10">
        <x-ledger-mark size="sm" />

        <p class="text-[10px] uppercase tracking-[0.16em] text-faint">
            © {{ date('Y') }} Ledger
        </p>
    </div>
</footer>