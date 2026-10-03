@props(['value', 'colors' => [], 'default' => 'text-[#8f826b]'])

<span {{ $attributes->merge(['class' => $colors[$value] ?? $default]) }}>{{ $value }}</span>