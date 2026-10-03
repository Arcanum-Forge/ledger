<div class="w-full max-w-md">

    <div class="mb-10 text-center">
        <div class="mb-4 flex items-center justify-center gap-3">
            <span class="h-px w-8 bg-[#806337]"></span>
            <span class="text-[9px] uppercase tracking-[0.28em] text-[#a17e43]">Enter the Archive</span>
            <span class="h-px w-8 bg-[#806337]"></span>
        </div>
        <h1 class="font-serif text-3xl text-[#e8dfca]">Welcome back, Keeper.</h1>
    </div>

    <form wire:submit="login" class="border border-[#2c2922] bg-[#151310] p-8">

        <div class="mb-5">
            <label class="mb-2 block text-[9px] font-semibold uppercase tracking-[0.2em] text-[#806337]">
                Email
            </label>
            <input type="email" wire:model="email"
                class="w-full border border-[#3b3225] bg-[#0d0c0a] px-4 py-3 text-sm text-[#e8dfca] outline-none transition focus:border-[#806337]"
                autofocus>
            @error('email')
                <p class="mt-2 text-[10px] uppercase tracking-[0.1em] text-[#b98967]">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="mb-2 block text-[9px] font-semibold uppercase tracking-[0.2em] text-[#806337]">
                Password
            </label>
            <input type="password" wire:model="password"
                class="w-full border border-[#3b3225] bg-[#0d0c0a] px-4 py-3 text-sm text-[#e8dfca] outline-none transition focus:border-[#806337]">
            @error('password')
                <p class="mt-2 text-[10px] uppercase tracking-[0.1em] text-[#b98967]">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-7 flex items-center justify-between">
            <label class="flex items-center gap-2 text-[9px] uppercase tracking-[0.15em] text-[#756d5e]">
                <input type="checkbox" wire:model="remember" class="border-[#3b3225] bg-[#0d0c0a] accent-[#806337]">
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-[9px] uppercase tracking-[0.15em] text-[#a17e43] transition hover:text-[#c59b4a]">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="login"
            class="flex w-full items-center justify-center gap-2 bg-[#806337] px-6 py-3.5 text-[9px] font-semibold uppercase tracking-[0.2em] text-[#eee5d1] transition hover:bg-[#967744] disabled:opacity-50">
            <span wire:loading.remove wire:target="login">Enter</span>
            <span wire:loading wire:target="login">Verifying...</span>
        </button>
    </form>

</div>