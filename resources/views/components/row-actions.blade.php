{{-- row-actions.blade.php --}}
@props(['id', 'island' => null])

<div class="flex items-center justify-end gap-4 text-[10px] uppercase tracking-[0.15em]">
    <button wire:click="openEdit('{{ $id }}')" @if ($island) wire:island="{{ $island }}" @endif
        class="text-gold-dim transition hover:text-gold">Edit</button>
    <button wire:click="openDelete('{{ $id }}')" @if ($island) wire:island="{{ $island }}" @endif
        class="text-muted transition hover:text-danger">Delete</button>
</div>