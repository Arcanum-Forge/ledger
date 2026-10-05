{{-- flash-message.blade.php --}}
@props(['floating' => false])

@if (session()->has('success'))
    <div wire:key="flash-{{ uniqid() }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
        x-show="show" x-transition role="status"
        class="{{ $floating ? 'shadow-lg shadow-black/40' : 'mb-6' }} border border-line-bronze bg-card px-5 py-4">
        <div class="flex items-center gap-3">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center border border-line-bronze bg-surface">
                <x-tabler-circle-check class="h-4 w-4 text-gold-dim" />
            </div>

            <div class="min-w-0 flex-1">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gold-dim">
                    Archive Updated
                </p>
                <p class="mt-1 text-xs text-parchment-dim">
                    {{ session('success') }}
                </p>
            </div>

            <button type="button" @click="show = false"
                class="shrink-0 text-faint transition hover:text-parchment-dim" aria-label="Dismiss">
                <x-tabler-x class="h-4 w-4" />
            </button>

        </div>
    </div>
@endif