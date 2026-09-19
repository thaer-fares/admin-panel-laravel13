<div>
    <x-page-header :title="__('Dashboard')" />

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-stat-card :label="__('Total Users')" :value="$stats['users']" accent="brand">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </x-stat-card>

        <x-stat-card :label="__('Active Users')" :value="$stats['active_users']" accent="success">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
        </x-stat-card>

        <x-stat-card :label="__('Roles')" :value="$stats['roles']" accent="gold">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l7 3v6c0 5-3.4 8.4-7 10-3.6-1.6-7-5-7-10V5l7-3z"/></svg>
        </x-stat-card>

        <x-stat-card :label="__('Permissions')" :value="$stats['permissions']" accent="danger">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        </x-stat-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-6">
        <x-card class="lg:col-span-2">
            <h3 class="text-sm font-semibold text-ink mb-4">{{ __('User growth (last 6 months)') }}</h3>
            <div class="h-64 sm:h-72" wire:ignore x-data="lineChart(@js($growth))" x-init="init($refs.canvas)">
                <canvas x-ref="canvas"></canvas>
            </div>
        </x-card>

        <x-card>
            <h3 class="text-sm font-semibold text-ink mb-4">{{ __('Users by role') }}</h3>
            <div class="h-64 sm:h-72" wire:ignore x-data="donutChart(@js($roleDistribution))" x-init="init($refs.canvas)">
                <canvas x-ref="canvas"></canvas>
            </div>
        </x-card>
    </div>

    <x-card class="mt-6">
        <h2 class="text-lg font-semibold text-ink mb-2">{{ __('Welcome') }}, {{ auth()->user()->name }} 👋</h2>
        <p class="text-ink-muted text-sm">
            {{ __('Use the sidebar to manage users, roles and permissions.') }}
        </p>
    </x-card>
</div>
