<div class="border-t border-line p-3">

    <div class="mb-2 flex items-center gap-3 px-3 py-3">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center border border-line-bronze bg-card">
            <x-tabler-user class="h-4 w-4 text-bronze-light" />
        </div>

        <div class="min-w-0">
            <p class="truncate text-[11px] font-semibold uppercase tracking-[0.15em] text-parchment-dim">
                {{ auth()->user()->name }}
            </p>
            <p class="mt-0.5 truncate text-[10px] uppercase tracking-[0.15em] text-faint">
                Keeper
            </p>
        </div>
    </div>

    <div x-data="{ confirmingLogout: false }">

        {{-- Trigger --}}
        <button type="button" @click="confirmingLogout = true"
            class="group flex w-full items-center gap-3 border border-transparent px-3 py-2.5 text-muted transition hover:border-line hover:bg-card hover:text-danger">
            <x-tabler-logout class="h-4 w-4" />
            <span class="text-[11px] font-semibold uppercase tracking-[0.18em]">
                Leave Ledger
            </span>
        </button>

        {{-- Modal --}}
        <template x-teleport="body">
            <div x-show="confirmingLogout" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center px-6">

                {{-- Backdrop --}}
                <div x-show="confirmingLogout" x-transition.opacity @click="confirmingLogout = false"
                    class="absolute inset-0 bg-black/75"></div>

                {{-- Panel --}}
                <div x-show="confirmingLogout" x-transition @keydown.escape.window="confirmingLogout = false"
                    role="dialog" aria-modal="true" aria-labelledby="logout-title"
                    class="relative w-full max-w-sm border border-line-bronze bg-card p-7">

                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-bronze"></span>
                        <span class="text-[10px] uppercase tracking-[0.28em] text-gold-dim">
                            Depart the Archive
                        </span>
                    </div>

                    <h3 id="logout-title" class="font-serif text-xl text-parchment-bright">
                        Leave the Ledger?
                    </h3>

                    <p class="mt-3 text-xs leading-6 text-muted">
                        You'll need to sign in again to continue tending the archive.
                    </p>

                    <div class="mt-8 flex items-center gap-3">
                        <button type="button" @click="confirmingLogout = false"
                            class="flex-1 border border-line px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-muted transition hover:border-bronze hover:text-parchment-dim">
                            Stay
                        </button>

                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full bg-bronze px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-ink transition hover:bg-bronze-light">
                                Leave Ledger
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </template>

    </div>
</div>