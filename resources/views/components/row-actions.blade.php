@props(['id'])

<div class="flex items-center justify-end gap-4 text-[9px] uppercase tracking-[0.15em]">
    <button wire:click="openEdit('{{ $id }}')" class="text-[#a17e43] transition hover:text-[#c59b4a]">Edit</button>
    <button wire:click="openDelete('{{ $id }}')" class="text-[#857861] transition hover:text-[#c14545]">Delete</button>
</div>