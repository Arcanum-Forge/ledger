{{-- components/landing/section-heading.blade.php --}}
@props(['highlight', 'as' => 'h2'])

<{{ $as }} {{ $attributes->class(['font-serif text-parchment']) }}>
    {{ $slot }}
    <span class="text-gold">{{ $highlight }}</span>
</{{ $as }}>