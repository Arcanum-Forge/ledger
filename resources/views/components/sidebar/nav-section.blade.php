@props(['title'])

<div class="mb-7">
    <p class="mb-3 px-3 text-[8px] font-semibold uppercase tracking-[0.3em] text-[#625744]">
        {{ $title }}
    </p>
    <div class="space-y-1">
        {{ $slot }}
    </div>
</div>