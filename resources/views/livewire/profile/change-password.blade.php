<div class="max-w-lg">
    <x-page-header :title="__('Change Password')" />

    @if (session('success'))
        <div class="bg-success/10 text-success text-sm px-4 py-2 rounded-xl mb-4">{{ session('success') }}</div>
    @endif

    <x-card>
        <form wire:submit="update" class="space-y-4">
            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('Current Password') }}</label>
                <input type="password" wire:model="current_password" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
                @error('current_password') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('New Password') }}</label>
                <input type="password" wire:model="new_password" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
                @error('new_password') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('Confirm New Password') }}</label>
                <input type="password" wire:model="new_password_confirmation" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
            </div>
            <x-button type="submit">{{ __('Update Password') }}</x-button>
        </form>
    </x-card>
</div>
