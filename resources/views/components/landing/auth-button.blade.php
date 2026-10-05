{{-- components/landing/auth-button.blade.php --}}
@props(['guest', 'member', 'size' => 'md', 'icon' => null])

<x-landing.button :href="auth()->check() ? route('dashboard') : route('login')" :size="$size" :icon="$icon" {{ $attributes }}>
    {{ auth()->check() ? $member : $guest }}
</x-landing.button>