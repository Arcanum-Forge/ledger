<div class="min-h-screen bg-[#0d0c0a] text-[#e8dfca] selection:bg-[#806337]/30 selection:text-[#eee5d1]">
    <header class="border-b border-[#2c2922]">
        <div class="mx-auto flex h-20 max-w-[1400px] items-center justify-between px-6 lg:px-10">
            <a href="{{ route('landing') }}" class="group">
                <x-ledger-mark />
            </a>

            <nav class="flex items-center gap-3 sm:gap-5 md:gap-8">
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                    class="border border-[#806337]/70 bg-[#201b13] px-3 py-2 text-[8px] font-semibold uppercase tracking-[0.15em] text-[#d2b26e] transition hover:bg-[#282116] sm:px-4 sm:py-2.5 sm:text-[9px] sm:tracking-[0.2em] md:px-5">
                    {{ auth()->check() ? 'Dashboard' : 'Enter Ledger' }}
                </a>

            </nav>
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden" id="hero">
            <div class="pointer-events-none absolute inset-0 opacity-20" style="
                        background-image:
                            linear-gradient(rgba(128,99,55,0.06) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(128,99,55,0.06) 1px, transparent 1px);
                        background-size: 80px 80px;
                    "></div>

            <div class="pointer-events-none absolute left-1/2 top-1/2
                        h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2
                        rounded-full bg-[#806337]/[0.035] blur-3xl"></div>


            <div class="relative mx-auto max-w-[1400px] px-6 py-28 lg:px-10 lg:py-40">
                <div class="max-w-5xl">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-[#806337]"></span>
                        <span class="text-[9px] uppercase tracking-[0.28em] text-[#a17e43]">
                            The Great Archive
                        </span>
                    </div>

                    <h1 class="font-serif text-6xl leading-[0.9]
                                tracking-[-0.035em]
                                sm:text-7xl md:text-8xl lg:text-[9rem]">
                        The world's
                        <span class="block text-[#c59b4a]">
                            living record.
                        </span>
                    </h1>

                    <p class="mt-10 max-w-2xl text-sm leading-8 text-[#756d5e] md:text-base">
                        A centralized archive for the kingdoms, factions, histories, creatures, and threats that define
                        your world.
                    </p>

                    <div class="mt-9 flex flex-wrap items-center gap-4">
                        <a href="#archive" class="group flex items-center gap-3
                                    bg-[#806337] px-6 py-3.5
                                    text-[9px] font-semibold uppercase
                                    tracking-[0.2em] text-[#eee5d1]
                                    transition hover:bg-[#967744]">

                            Explore the Archive

                            <svg class="h-3.5 w-3.5 transition-transform
                                        group-hover:translate-x-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </a>

                    </div>
                </div>
            </div>
        </section>


        <section id="archive" class="border-y border-[#2c2922] bg-[#11100e]">
            <div class="mx-auto max-w-[1400px] px-6 py-24 lg:px-10 lg:py-32">
                <div class="mb-14 max-w-2xl">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="h-px w-8 bg-[#806337]"></span>
                        <span class="text-[9px] uppercase tracking-[0.28em] text-[#a17e43]">Within the Archive</span>
                    </div>
                    <h2 class="font-serif text-4xl text-[#e8dfca] md:text-5xl">
                        Everything has <span class="text-[#c59b4a]">a record.</span>
                    </h2>
                    <p class="mt-5 text-sm leading-7 text-[#756d5e]">
                        Ledger gives every corner of your fantasy world a place in history. Build the world, then
                        preserve it.
                    </p>
                </div>

                <div class="grid border-l border-t border-[#302a20] sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($pillars as $pillar)
                        <x-ledger.pillar-card :pillar="$pillar" />
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-[#0d0c0a]">
        <section class="border-b border-[#2c2922]">
            <div class="mx-auto max-w-[1000px] px-6 py-28
                            text-center lg:py-36">
                <div class="mb-5 text-[9px] uppercase tracking-[0.3em]
                                text-[#806337]">
                    Your world awaits
                </div>

                <h2 class="font-serif text-4xl text-[#e8dfca]
                            md:text-6xl">
                    Begin your
                    <span class="text-[#c59b4a]">
                        archive.
                    </span>
                </h2>

                <p class="mx-auto mt-6 max-w-lg text-sm leading-7
                            text-[#756d5e]">
                    Give your world a place to remember itself.
                </p>


                @auth
                    <a href="{{ route('dashboard') }}"
                        class="group mx-auto mt-9 flex w-fit items-center gap-3 border border-[#806337] bg-[#201b13] px-7 py-4 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#d2b26e] transition-all hover:bg-[#282116]">
                        Enter the Archive
                        <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 19L19 5M8 5h11v11" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="group mx-auto mt-9 flex w-fit items-center gap-3 border border-[#806337] bg-[#201b13] px-7 py-4 text-[10px] font-semibold uppercase tracking-[0.2em] text-[#d2b26e] transition-all hover:bg-[#282116]">
                        Enter the Archive
                        <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 19L19 5M8 5h11v11" />
                        </svg>
                    </a>
                @endauth
            </div>
        </section>


        <div class="mx-auto flex max-w-[1400px] flex-col gap-6 px-6
                        py-8 md:flex-row md:items-center
                        md:justify-between lg:px-10">

            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center
                                border border-[#55452c] bg-[#151310]">
                    <span class="font-serif text-sm text-[#a17e43]">
                        <x-tabler-crown />
                    </span>
                </div>
                <div>

                    <div class="font-serif text-sm tracking-[0.16em]
                                    text-[#ddd2bb]">
                        LEDGER
                    </div>

                    <div class="text-[7px] uppercase tracking-[0.24em]
                                    text-[#59544b]">
                        The Great Archive
                    </div>
                </div>
            </div>

            <div class="text-[8px] uppercase tracking-[0.16em]
                            text-[#403b32]">

                © {{ date('Y') }} Ledger
            </div>
        </div>
    </footer>
</div>