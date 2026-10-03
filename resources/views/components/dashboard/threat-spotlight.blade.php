@props([
    'kingdom',
])

@if ($kingdom)
    <div class="mb-8 border border-[#2c2922] bg-[#151310] p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-[9px] uppercase tracking-[0.25em] text-[#806337]">
                    Highest Threat Kingdom
                </p>

                <p class="mt-2 font-serif text-2xl text-[#e8dfca]">
                    {{ $kingdom->name }}
                </p>

                <p class="mt-1 text-xs text-[#8f826b]">
                    {{ $kingdom->title }}
                    · Ruled by {{ $kingdom->ruler->full_title ?? 'no one' }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-[9px] uppercase tracking-[0.2em] text-[#625744]">
                    Threat Level
                </p>

                <p class="mt-1 font-serif text-4xl {{ $kingdom->threat_color ?? 'text-[#c14545]' }}">
                    {{ $kingdom->threat }}
                </p>
            </div>
        </div>
    </div>
@endif