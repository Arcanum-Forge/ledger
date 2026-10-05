<div x-data="{ open: false }" @keydown.escape.window="open = false"
    class="fixed bottom-[max(1rem,env(safe-area-inset-bottom))] right-[max(1rem,env(safe-area-inset-right))] z-50">
    {{-- Backdrop (mobile only) --}}
    <div x-show="open" x-cloak x-transition.opacity @click="open = false"
        class="fixed inset-0 -z-10 bg-black/60 sm:hidden"></div>

    {{-- Panel: bottom sheet on mobile, popover on sm+ --}}
    <div x-show="open" x-cloak x-transition.duration.150ms @click.outside="open = false" class="fixed inset-x-3 bottom-[calc(max(1rem,env(safe-area-inset-bottom))+4.5rem)] max-h-[70dvh] overflow-y-auto rounded-xl border border-line-bronze bg-card p-3 shadow-2xl shadow-black/50
               sm:absolute sm:inset-x-auto sm:bottom-14 sm:right-0 sm:w-56 sm:max-h-none sm:rounded-lg">
        <p class="mb-2 px-1 text-xs uppercase tracking-widest text-faint">Theme</p>

        <div class="space-y-1">
            @foreach ($themes as $key => $theme)
                <button type="button" wire:key="theme-{{ $key }}" wire:click="setTheme('{{ $key }}')"
                    @click="document.documentElement.dataset.theme = '{{ $key }}'; open = false" @class([
                        'flex min-h-12 w-full items-center justify-between rounded-md border px-3 py-2 text-base transition sm:min-h-10 sm:text-sm',
                        'border-gold bg-card-hover text-gold' => $this->theme === $key,
                        'border-transparent text-parchment-dim active:bg-card-hover sm:hover:bg-card-hover' => $this->theme !== $key,
                    ])>
                    <span>{{ $theme['label'] }}</span>

                    <span class="flex -space-x-1">
                        @foreach ($theme['colors'] as $color)
                            <span class="size-5 rounded-full ring-1 ring-black/40 sm:size-4"
                                style="background-color: {{ $color }}"></span>
                        @endforeach
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Gear button --}}
    <button type="button" @click="open = !open" :aria-expanded="open" aria-label="Change theme"
        class="flex size-12 items-center justify-center rounded-full border border-line-bronze bg-card text-gold shadow-lg shadow-black/40 transition active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold sm:size-11 sm:hover:bg-card-hover sm:hover:text-gold-soft">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
            class="transition-transform duration-300" :class="open && 'rotate-90'">
            <path
                d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
        </svg>
    </button>
</div>