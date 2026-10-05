{{-- components/landing/grid-backdrop.blade.php --}}
@props(['size' => 80])

<div aria-hidden="true" {{ $attributes->class(['pointer-events-none absolute inset-0 opacity-20']) }} style="background-image:
            linear-gradient(rgba(128,99,55,.06) 1px, transparent 1px),
            linear-gradient(90deg, rgba(128,99,55,.06) 1px, transparent 1px);
        background-size: {{ $size }}px {{ $size }}px;">
</div>