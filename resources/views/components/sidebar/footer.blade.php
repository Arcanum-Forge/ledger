<div class="border-t border-[#806337]/25 p-3">

    <div class="mb-2 flex items-center gap-3 px-3 py-3">

        <div class="flex h-8 w-8 shrink-0 items-center justify-center border border-[#806337]/40 bg-[#211c14]">
            <x-tabler-user class="h-4 w-4 text-[#a88b5b]" />
        </div>

        <div class="min-w-0">
            <p class="truncate text-[9px] font-semibold uppercase tracking-[0.15em] text-[#c8b895]">
                {{ auth()->user()->name  }}
            </p>

            <p class="mt-0.5 truncate text-[8px] uppercase tracking-[0.15em] text-[#625744]">
                Keeper
            </p>
        </div>

    </div>



    <div x-data="{ confirmingLogout: false }">

        {{-- Trigger --}}
        <button type="button" @click="confirmingLogout = true"
            class="group flex w-full items-center gap-3 border border-transparent px-3 py-2.5 text-[#857861] transition hover:border-[#806337]/20 hover:bg-[#211c14] hover:text-[#b98967]">

            <x-tabler-logout class="h-4 w-4" />

            <span class="text-[9px] font-semibold uppercase tracking-[0.18em]">
                Leave Ledger
            </span>
        </button>


        {{-- Modal --}}
        <template x-teleport="body">

            <div x-show="confirmingLogout" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center px-6">

                {{-- Backdrop --}}
                <div x-show="confirmingLogout" x-transition.opacity @click="confirmingLogout = false"
                    class="absolute inset-0 bg-[#0d0c0a]/80">
                </div>


                {{-- Panel --}}
                <div x-show="confirmingLogout" x-transition @keydown.escape.window="confirmingLogout = false"
                    class="relative w-full max-w-sm border border-[#806337]/40 bg-[#151310] p-7">

                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-8 bg-[#806337]"></span>

                        <span class="text-[9px] uppercase tracking-[0.28em] text-[#a17e43]">
                            Depart the Archive
                        </span>
                    </div>

                    <h3 class="font-serif text-xl text-[#e8dfca]">
                        Leave the Ledger?
                    </h3>

                    <p class="mt-3 text-xs leading-6 text-[#756d5e]">
                        You'll need to sign in again to continue tending the archive.
                    </p>

                    <div class="mt-8 flex items-center gap-3">

                        <button type="button" @click="confirmingLogout = false"
                            class="flex-1 border border-[#3b3225] px-4 py-3 text-[9px] font-semibold uppercase tracking-[0.18em] text-[#857861] transition hover:border-[#806337]/40 hover:text-[#c8b895]">
                            Stay
                        </button>

                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf

                            <button type="submit"
                                class="w-full bg-[#806337] px-4 py-3 text-[9px] font-semibold uppercase tracking-[0.18em] text-[#eee5d1] transition hover:bg-[#967744]">
                                Leave Ledger
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </template>

    </div>

</div>