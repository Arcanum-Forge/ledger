@props(['label'])

<div>
    <p class="text-[8px] uppercase tracking-[0.15em] text-[#625744]">{{ $label }}</p>
    <p {{ $attributes->merge(['class' => 'mt-1 text-[#cdbd9e]']) }}>{{ $slot }}</p>
</div>