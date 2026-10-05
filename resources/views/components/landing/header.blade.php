{{-- components/landing/header.blade.php --}}
<header class="border-b border-line">
    <div class="mx-auto flex h-20 max-w-[1400px] items-center justify-between px-6 lg:px-10">
        <a href="{{ route('landing') }}" aria-label="Ledger home">
            <x-ledger-mark />
        </a>

        <nav aria-label="Primary">
            <x-landing.auth-button guest="Enter Ledger" member="Dashboard" size="sm" />
        </nav>
    </div>
</header>