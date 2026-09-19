<div class="min-h-screen flex flex-col lg:flex-row" style="animation: panel-in .6s ease-out both">

    {{-- اللوحة التعريفية --}}
    <div class="relative lg:w-[46%] overflow-hidden text-white flex flex-col justify-between px-8 py-10 lg:px-14 lg:py-14"
         style="background: radial-gradient(120% 140% at 15% 0%, #17685D 0%, #0F4A43 42%, #0A2E2A 100%);">

        {{-- نقاط زخرفية خفيفة --}}
        <div class="absolute inset-0 opacity-[0.15] pointer-events-none"
             style="background-image: radial-gradient(#E4CD93 1px, transparent 1px); background-size: 22px 22px;"></div>

        {{-- شكل دائري ضبابي كبير --}}
        <div class="absolute -bottom-24 -start-24 w-80 h-80 rounded-full pointer-events-none"
             style="background: #C9A24B; opacity: 0.18; filter: blur(60px);"></div>

        <div class="relative">
            {{-- الشعار: أيقونة درع + صح --}}
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-10"
                 style="background: linear-gradient(135deg, #E4CD93, #C9A24B);">
                <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="#0A2E2A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l7 3v6c0 5-3.4 8.4-7 10-3.6-1.6-7-5-7-10V5l7-3z"/>
                    <path d="M9.5 12l1.8 1.8L15 10"/>
                </svg>
            </div>

            <h1 class="text-3xl lg:text-[2.2rem] font-bold leading-snug max-w-xs">
                {{ __('Manage your team and system from one place') }}
            </h1>
            <p class="mt-4 text-sm max-w-xs" style="color: #B9CFC9;">
                {{ __('Admin Panel') }}
            </p>
        </div>

        <div class="relative space-y-4 mt-12 lg:mt-0">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(228,205,147,0.15);">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="#E4CD93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </span>
                <span class="text-sm" style="color: #D7E5E1;">{{ __('Full roles and permissions control') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(228,205,147,0.15);">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="#E4CD93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/></svg>
                </span>
                <span class="text-sm" style="color: #D7E5E1;">{{ __('Real-time notifications inside the system') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(228,205,147,0.15);">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="#E4CD93" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                </span>
                <span class="text-sm" style="color: #D7E5E1;">{{ __('Instant search and live data filters') }}</span>
            </div>
        </div>
    </div>

    {{-- نموذج الدخول --}}
    <div class="flex-1 flex items-center justify-center px-6 py-14 lg:py-10">
        <div class="w-full max-w-sm">

            <div class="mb-9">
                <h2 class="text-2xl font-bold" style="color: #16231F;">{{ __('Admin Panel') }}</h2>
                <p class="text-sm mt-1.5" style="color: #64766F;">{{ __('Sign in to your account') }}</p>
            </div>

            <form wire:submit="login" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color: #16231F;">{{ __('Email') }}</label>
                    <input type="email" wire:model="email" autofocus autocomplete="username"
                        class="w-full rounded-xl border text-sm text-start px-4 py-3 transition focus:outline-none focus:ring-2"
                        style="border-color:#E4E1DA; --tw-ring-color:#C9A24B;">
                    @error('email') <span class="text-xs mt-1.5 block" style="color:#C0392B;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color: #16231F;">{{ __('Password') }}</label>
                    <input type="password" wire:model="password" autocomplete="current-password"
                        class="w-full rounded-xl border text-sm text-start px-4 py-3 transition focus:outline-none focus:ring-2"
                        style="border-color:#E4E1DA; --tw-ring-color:#C9A24B;">
                    @error('password') <span class="text-xs mt-1.5 block" style="color:#C0392B;">{{ $message }}</span> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm" style="color: #64766F;">
                    <input type="checkbox" wire:model="remember" class="rounded" style="accent-color:#0F4A43; border-color:#E4E1DA;">
                    {{ __('Remember me') }}
                </label>

                <button type="submit"
                    class="w-full text-white font-medium py-3 rounded-xl transition shadow-sm hover:shadow-md"
                    style="background: linear-gradient(135deg, #17685D, #0A2E2A);"
                    wire:loading.attr="disabled" wire:target="login">
                    <span wire:loading.remove wire:target="login">{{ __('Sign in') }}</span>
                    <span wire:loading wire:target="login">{{ __('Signing in...') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
