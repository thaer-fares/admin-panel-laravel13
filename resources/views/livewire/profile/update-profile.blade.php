<div class="max-w-lg">
    <x-page-header :title="__('My Profile')" />

    @if (session('success'))
        <div class="bg-success/10 text-success text-sm px-4 py-2 rounded-xl mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-danger/10 text-danger text-sm px-4 py-2 rounded-xl mb-4">{{ session('error') }}</div>
    @endif

    <x-card>
        <form wire:submit="update" class="space-y-4">
            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('Name') }}</label>
                <input type="text" wire:model="name" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
                @error('name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('Email') }}</label>
                <input type="email" wire:model="email" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
                @error('email') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('Phone') }}</label>
                <input type="text" wire:model="phone" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <x-button type="submit">{{ __('Save Changes') }}</x-button>
                <x-button variant="secondary" wire:click="sendTestEmail" wire:loading.attr="disabled" wire:target="sendTestEmail">
                    {{ __('Send test email') }}
                </x-button>
            </div>
        </form>
    </x-card>
</div>
