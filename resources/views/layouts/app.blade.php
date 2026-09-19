<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Admin Panel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-app-bg font-sans antialiased">
    <div class="flex min-h-screen">
        {{-- الشريط الجانبي --}}
        <aside class="w-64 flex-shrink-0 hidden lg:flex flex-col text-white"
               style="background: linear-gradient(180deg, #0F4A43 0%, #0A2E2A 100%);">
            <div class="h-16 flex items-center gap-2.5 px-6 border-b border-white/10">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                     style="background: linear-gradient(135deg, #E4CD93, #C9A24B);">
                    <svg viewBox="0 0 24 24" class="w-4.5 h-4.5" fill="none" stroke="#0A2E2A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l7 3v6c0 5-3.4 8.4-7 10-3.6-1.6-7-5-7-10V5l7-3z"/>
                        <path d="M9.5 12l1.8 1.8L15 10"/>
                    </svg>
                </div>
                <span class="font-bold text-[15px]">{{ __('Admin Panel') }}</span>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                    {{ __('Dashboard') }}
                </a>
                @can('manage-users')
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('users.*') ? 'bg-white/15 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    {{ __('Users') }}
                </a>
                @endcan
                @can('manage-roles')
                <a href="{{ route('roles.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('roles.*') ? 'bg-white/15 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l7 3v6c0 5-3.4 8.4-7 10-3.6-1.6-7-5-7-10V5l7-3z"/></svg>
                    {{ __('Roles') }}
                </a>
                @endcan
                @can('manage-permissions')
                <a href="{{ route('permissions.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('permissions.*') ? 'bg-white/15 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    {{ __('Permissions') }}
                </a>
                @endcan

                <div class="pt-4 mt-4 border-t border-white/10 space-y-1">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('profile.*') ? 'bg-white/15 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 016-6h4a6 6 0 016 6v1"/></svg>
                        {{ __('My Profile') }}
                    </a>
                    <a href="{{ route('password.edit') }}"
                        class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('password.*') ? 'bg-white/15 text-white font-medium' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        {{ __('Change Password') }}
                    </a>
                </div>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            {{-- الشريط العلوي --}}
            <header class="h-16 bg-white border-b border-border flex items-center justify-between px-4 sm:px-6">
                <div class="text-sm text-ink-muted">
                    {{ now()->translatedFormat('l، d F Y') }}
                </div>
                <div class="flex items-center gap-3">
                    {{-- مبدل اللغة --}}
                    <div class="flex text-xs border border-border rounded-full overflow-hidden">
                        <a href="{{ route('lang.switch', 'ar') }}" class="px-3 py-1 transition {{ app()->getLocale() === 'ar' ? 'bg-brand-800 text-white' : 'text-ink-muted' }}">AR</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 transition {{ app()->getLocale() === 'en' ? 'bg-brand-800 text-white' : 'text-ink-muted' }}">EN</a>
                    </div>

                    @livewire('notifications.bell')

                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-brand-950"
                                  style="background: linear-gradient(135deg, #E4CD93, #C9A24B);">
                                {{ auth()->user()->initials() }}
                            </span>
                            <span class="text-sm text-ink hidden sm:inline">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="open" x-cloak class="absolute end-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-border py-1 text-sm z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-app-bg text-ink">{{ __('My Profile') }}</a>
                            <a href="{{ route('password.edit') }}" class="block px-4 py-2 hover:bg-app-bg text-ink">{{ __('Change Password') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-start px-4 py-2 hover:bg-app-bg text-danger">{{ __('Logout') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
