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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="flex min-h-screen items-center justify-center bg-ink text-parchment px-6">

    {{ $slot }}

    @livewireScripts
</body>

</html>