@props(['model', 'label', 'rows' => 3])

<div>
    <label class="mb-1.5 block text-[9px] uppercase tracking-[0.2em] text-[#806337]">{{ $label }}</label>
    <textarea wire:model="{{ $model }}" rows="{{ $rows }}"
        class="w-full border border-[#3b3225] bg-[#0d0c0a] px-4 py-2.5 text-sm text-[#e8dfca] outline-none focus:border-[#806337]"></textarea>

    @error($model)
        <p class="mt-1.5 text-[10px] text-[#c14545]">{{ $message }}</p>
    @enderror
</div>