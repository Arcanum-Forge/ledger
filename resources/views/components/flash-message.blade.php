{{-- Flash Message --}}
@if (session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="mb-6 border border-[#806337]/40 bg-[#211c14] px-5 py-4">
        <div class="flex items-center gap-3">

            {{-- Success Icon --}}
            <div class="flex h-8 w-8 shrink-0 items-center justify-center border border-[#806337]/40 bg-[#241f16]">
                <x-tabler-circle-check class="h-4 w-4 text-[#a17e43]" />
            </div>

            <div class="min-w-0 flex-1">
                <p class="text-[9px] font-semibold uppercase tracking-[0.2em] text-[#a17e43]">
                    Archive Updated
                </p>

                <p class="mt-1 text-xs text-[#c8b895]">
                    {{ session('success') }}
                </p>
            </div>

            {{-- Dismiss --}}
            <button type="button" @click="show = false" class="shrink-0 text-[#625744] transition hover:text-[#c8b895]"
                aria-label="Dismiss">
                <x-tabler-x class="h-4 w-4" />
            </button>

        </div>
    </div>
@endif