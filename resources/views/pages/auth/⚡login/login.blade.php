{{-- ⚡login/login.blade.php --}}
<div class="w-full max-w-md">
    <div class="mb-10 text-center">
        <x-ui.eyebrow>Enter the Archive</x-ui.eyebrow>
        <h1 class="font-serif text-3xl text-parchment">Welcome back, Keeper.</h1>
    </div>

    <form wire:submit="login" class="border border-line bg-card p-8">
        <x-auth.field label="Email" name="email" type="email" wire:model="email" autocomplete="username" autofocus />
        <x-auth.field label="Password" name="password" type="password" wire:model="password"
            autocomplete="current-password" />

        <div class="mb-7 flex items-center justify-between">
            <label class="flex items-center gap-2 text-[9px] uppercase tracking-[0.15em] text-muted">
                <input type="checkbox" wire:model="remember" class="border-line-bronze bg-ink accent-bronze">
                Remember me
            </label>

            @if (Route::has('password.request'))
                <x-ui.link href="{{ route('password.request') }}">Forgot password?</x-ui.link>
            @endif
        </div>

        <x-ui.button target="login" loading="Verifying...">Enter</x-ui.button>

        <x-ui.link variant="faint" href="{{ route('landing') }}" wire:navigate class="mt-4 inline-block">
            ← Return to the Realm
        </x-ui.link>
    </form>
</div>