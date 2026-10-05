@php
    $themes = [
        'default' => 'Bronze',
        'verdant' => 'Verdant',
        'crimson' => 'Crimson',
        'frost' => 'Frost',
        'amethyst' => 'Amethyst',
    ];
    $theme = request()->cookie('theme');
    $theme = array_key_exists($theme, $themes) ? $theme : 'default';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ $theme }}" x-data
    @theme-changed.window="document.documentElement.dataset.theme = $event.detail.theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-ink font-sans text-parchment antialiased">

    <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false"
        class="flex h-dvh overflow-hidden">

        {{-- Mobile backdrop --}}
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/60 lg:hidden" x-cloak></div>

        {{-- Sidebar --}}
        <aside aria-label="Primary"
            class="fixed inset-y-0 left-0 z-50 w-64 transform border-r border-line bg-ink-raised transition-transform duration-200 ease-out lg:static lg:translate-x-0 lg:shrink-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <x-sidebar />
        </aside>

        {{-- Main content --}}
        <main class="min-w-0 flex-1 overflow-y-auto">

            {{-- Mobile header --}}
            <header
                class="sticky top-0 z-30 flex h-16 items-center border-b border-line bg-ink/95 px-5 backdrop-blur lg:hidden">

                <button type="button" @click="sidebarOpen = true"
                    class="flex h-9 w-9 items-center justify-center rounded border border-line-bronze text-parchment-dim transition hover:border-bronze hover:text-parchment-bright focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold"
                    aria-label="Open navigation">
                    <x-tabler-menu class="h-5 w-5" />
                </button>

                <div class="ml-4 text-xs font-medium uppercase tracking-[0.2em] text-muted">
                    Ledger
                </div>

            </header>

            <div class="mx-auto max-w-[1600px] px-5 py-8 sm:px-8 lg:py-12">
                {{ $slot }}
            </div>

        </main>

    </div>

    @livewireScripts
    <livewire:theme-switcher />
</body>

</html>