@props([
    'rows' => 5,
])

<div class="border border-[#2c2922] bg-[#151310]">
    {{-- Panel header --}}
    <div class="flex items-center justify-between border-b border-[#2c2922] px-5 py-4">
        <div class="h-2 w-32 animate-pulse bg-[#2c2922]"></div>

        <div class="h-2 w-12 animate-pulse bg-[#2c2922]"></div>
    </div>

    {{-- Rows --}}
    <div class="divide-y divide-[#2c2922]/60">
        @for ($i = 0; $i < $rows; $i++)
            <div class="flex items-center justify-between gap-4 px-5 py-4">
                <div class="min-w-0 flex-1">
                    {{-- Title --}}
                    <div class="h-3 w-3/5 animate-pulse bg-[#2c2922]"></div>

                    {{-- Metadata --}}
                    <div class="mt-2 h-2 w-2/5 animate-pulse bg-[#2c2922]"></div>
                </div>

                {{-- Right-side value / badge --}}
                <div class="h-2 w-14 shrink-0 animate-pulse bg-[#2c2922]"></div>
            </div>
        @endfor
    </div>
</div>