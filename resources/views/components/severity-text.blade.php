{{-- severity-text.blade.php --}}
@props(['value', 'colors' => [], 'default' => 'text-muted'])

<span {{ $attributes->merge(['class' => $colors[$value] ?? $default]) }}>{{ $value }}</span>