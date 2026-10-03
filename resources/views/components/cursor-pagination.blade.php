@props(['paginator', 'total'])

<div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-[9px] uppercase tracking-[0.15em] text-[#625744]">
        Showing {{ $paginator->count() }} of {{ $total }}
    </p>

    <div class="flex w-full gap-2 sm:w-auto">
        <button wire:click="goToCursor('{{ $paginator->previousCursor()?->encode() }}')" @if (!$paginator->previousCursor()) disabled @endif
            class="flex-1 border border-[#3b3225] px-4 py-2 text-[9px] uppercase tracking-[0.15em] text-[#857861] transition hover:border-[#806337]/40 hover:text-[#c8b895] disabled:opacity-30 sm:flex-none">
            ← Previous
        </button>

        <button wire:click="goToCursor('{{ $paginator->nextCursor()?->encode() }}')" @if (!$paginator->hasMorePages())
        disabled @endif
            class="flex-1 border border-[#3b3225] px-4 py-2 text-[9px] uppercase tracking-[0.15em] text-[#857861] transition hover:border-[#806337]/40 hover:text-[#c8b895] disabled:opacity-30 sm:flex-none">
            Next →
        </button>
    </div>
</div>