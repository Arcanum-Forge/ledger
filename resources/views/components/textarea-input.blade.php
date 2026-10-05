{{-- textarea-input.blade.php --}}
@props(['model', 'label', 'rows' => 3])

<div>
    <label class="mb-1.5 block text-[10px] uppercase tracking-[0.2em] text-gold-dim">{{ $label }}</label>
    <textarea wire:model="{{ $model }}" rows="{{ $rows }}"
        class="w-full border border-line bg-card-inset px-4 py-2.5 text-sm text-parchment-bright outline-none focus:border-bronze"></textarea>

    @error($model)
        <p class="mt-1.5 text-[11px] text-danger">{{ $message }}</p>
    @enderror
</div>