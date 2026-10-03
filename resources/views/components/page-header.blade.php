@props(['eyebrow', 'title'])

<div class="mb-8 flex items-center justify-between">
    <div>
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#806337]">{{ $eyebrow }}</p>
        <h1 class="mt-1 font-serif text-3xl text-[#e8dfca]">{{ $title }}</h1>
    </div>

    @isset($actions)
        <div class="flex items-center gap-3">{{ $actions }}</div>
    @endisset
</div>