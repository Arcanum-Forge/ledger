{{-- page-header.blade.php --}}
@props(['eyebrow', 'title'])

<div class="mb-8 flex items-center justify-between">
    <div>
        <p class="text-[10px] uppercase tracking-[0.3em] text-gold-dim">{{ $eyebrow }}</p>
        <h1 class="mt-1 font-serif text-3xl text-parchment-bright">{{ $title }}</h1>
    </div>

    @isset($actions)
        <div class="flex items-center gap-3">{{ $actions }}</div>
    @endisset
</div>