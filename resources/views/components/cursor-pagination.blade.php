{{-- cursor-pagination.blade.php --}}
@props(['paginator', 'total'])

@php
    $pageBtn = 'flex-1 border border-line px-4 py-2 text-[11px] uppercase tracking-[0.15em] text-muted transition hover:border-bronze hover:text-parchment-dim disabled:opacity-30 sm:flex-none';
@endphp

<div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-[10px] uppercase tracking-[0.15em] text-faint">
        Showing {{ $paginator->count() }} of {{ $total }}
    </p>

    <div class="flex w-full gap-2 sm:w-auto">
        <button wire:click="goToCursor('{{ $paginator->previousCursor()?->encode() }}')"
            @if (!$paginator->previousCursor()) disabled @endif class="{{ $pageBtn }}">
            ← Previous
        </button>

        <button wire:click="goToCursor('{{ $paginator->nextCursor()?->encode() }}')"
            @if (!$paginator->hasMorePages()) disabled @endif class="{{ $pageBtn }}">
            Next →
        </button>
    </div>
</div>