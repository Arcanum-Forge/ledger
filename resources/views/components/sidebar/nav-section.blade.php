@props(['title'])

<div class="mb-7">
    <p class="mb-3 px-3 text-[10px] font-semibold uppercase tracking-[0.3em] text-faint">
        {{ $title }}
    </p>
    <div class="space-y-1">
        {{ $slot }}
    </div>
</div>