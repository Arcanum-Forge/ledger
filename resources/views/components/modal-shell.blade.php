@props(['show' => 'showModal'])

<div x-data="{ show: @entangle($show) }" x-show="show" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center px-6 py-10" style="display: none;">

    <div x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:click="closeModal"
        class="absolute inset-0 bg-[#0d0c0a]/85"></div>

    <div x-show="show" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" @keydown.escape.window="show = false"
        {{ $attributes->merge(['class' => 'relative max-h-[90vh] w-full max-w-lg overflow-y-auto border border-[#806337]/40 bg-[#151310] p-8']) }}>
        {{ $slot }}
    </div>
</div>