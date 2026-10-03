<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="flex min-h-screen items-center justify-center bg-[#0d0c0a] text-[#e8dfca] px-6">

    <div class="absolute left-6 top-6 sm:left-10 sm:top-10">
        <a href="{{ route('landing') }}">
            <x-ledger-mark size="sm" />
        </a>
    </div>

    {{ $slot }}

    @livewireScripts
</body>

</html>