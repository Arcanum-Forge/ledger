@props([
    'count' => 0,
])

<div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
    @for ($i = 0; $i < $count; $i++)
        <div class="border border-[#2c2922] bg-[#151310] p-5">
            <div class="h-2 w-20 animate-pulse bg-[#2c2922]"></div>

            <div class="mt-3 h-8 w-12 animate-pulse bg-[#2c2922]"></div>
        </div>
    @endfor
</div>