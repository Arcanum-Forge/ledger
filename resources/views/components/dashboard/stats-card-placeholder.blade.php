{{-- dashboard/stats-card-placeholder.blade.php --}}
@props([
    'count' => 0,
])

<div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5" aria-hidden="true">
    @for ($i = 0; $i < $count; $i++)
        <div class="border border-line bg-card p-5">
            <div class="h-2 w-20 animate-pulse bg-surface"></div>
            <div class="mt-3 h-8 w-12 animate-pulse bg-surface"></div>
        </div>
    @endfor
</div>