@props(['label', 'last' => false])

<div class="{{ $last ? 'p-5' : 'border-b border-r border-[#2c2922] p-5 sm:border-b-0' }}">
    <p class="text-[9px] uppercase tracking-[0.25em] text-[#806337]">{{ $label }}</p>
    <div class="mt-2">{{ $slot }}</div>
</div>