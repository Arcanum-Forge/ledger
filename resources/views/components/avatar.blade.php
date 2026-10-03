@props(['user', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-8 w-8 text-[10px]',
        'md' => 'h-9 w-9 text-[10px]',
        'lg' => 'h-14 w-14 text-lg',
    ];
@endphp

<span
    class="flex shrink-0 items-center justify-center rounded-full bg-[#806337]/20 font-semibold uppercase text-[#c59b4a] {{ $sizes[$size] ?? $sizes['md'] }}">
    {{ $user->initials() }}
</span>