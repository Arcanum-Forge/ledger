{{-- dashboard/threat-spotlight.blade.php --}}
@props([
    'kingdom',
])

@if ($kingdom)
    <div class="mb-8 border border-line bg-card p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-[10px] uppercase tracking-[0.25em] text-gold-dim">
                    Highest Threat Kingdom
                </p>

                <p class="mt-2 font-serif text-2xl text-parchment-bright">
                    {{ $kingdom->name }}
                </p>

                <p class="mt-1 text-xs text-muted">
                    {{ $kingdom->title }}
                    · Ruled by {{ $kingdom->ruler->full_title ?? 'no one' }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-[10px] uppercase tracking-[0.2em] text-faint">
                    Threat Level
                </p>

                <p class="mt-1 font-serif text-4xl {{ $kingdom->threat_color ?? 'text-threat-critical' }}">
                    {{ $kingdom->threat }}
                </p>
            </div>
        </div>
    </div>
@endif