<div>
    <x-page-header :title="__('Notifications')">
        <x-slot:action>
            <x-button variant="secondary" wire:click="markAllAsRead">{{ __('Mark all as read') }}</x-button>
        </x-slot:action>
    </x-page-header>

    <x-card padding="p-0" class="divide-y divide-border">
        @forelse ($notifications as $notification)
            <div class="px-5 py-4 {{ $notification->read_at ? '' : 'bg-gold-300/10' }}">
                <p class="text-sm font-medium text-ink">{{ $notification->data['title'] ?? '' }}</p>
                <p class="text-sm text-ink-muted mt-0.5">{{ $notification->data['body'] ?? '' }}</p>
                <p class="text-xs text-ink-muted/70 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-center text-ink-muted text-sm py-10">{{ __('No notifications') }}</p>
        @endforelse
    </x-card>

    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
