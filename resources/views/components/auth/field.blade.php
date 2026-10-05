@props(['label', 'name', 'type' => 'text'])

<div class="mb-5">
    <label for="{{ $name }}" class="mb-2 block text-[9px] font-semibold uppercase tracking-[0.2em] text-bronze">
        {{ $label }}
    </label>
    <input id="{{ $name }}" type="{{ $type }}" {{ $attributes->merge(['class' => 'w-full border border-line-bronze bg-ink px-4 py-3 text-sm text-parchment outline-none transition focus:border-bronze']) }}>
    @error($name)
        <p class="mt-2 text-[10px] uppercase tracking-[0.1em] text-danger">{{ $message }}</p>
    @enderror
</div>