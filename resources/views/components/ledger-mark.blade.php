@props(['size' => 'md'])

@php
    $box = $size === 'sm' ? 'h-8 w-8' : 'h-9 w-9';
@endphp

<div class="flex items-center gap-3">
    <div @class(["flex items-center justify-center border border-[#55452c] bg-[#151310]", $box])>
        <span class="font-serif text-sm text-[#a17e43]">
            <x-tabler-crown />
        </span>
    </div>
    <div>
        <div class="font-serif text-sm tracking-[0.16em] text-[#ddd2bb]">LEDGER</div>
        <div class="text-[7px] uppercase tracking-[0.24em] text-[#59544b]">The Great Archive</div>
    </div>
</div>