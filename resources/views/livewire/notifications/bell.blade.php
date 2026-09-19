<div class="relative" x-data @click.outside="$wire.open = false">
    <button wire:click="toggle" class="relative p-2 rounded-full hover:bg-app-bg">
        <svg class="w-5 h-5 text-ink-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
        </svg>
        @if ($unreadCount > 0)
            <span class="absolute -top-0.5 -end-0.5 bg-danger text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    @if ($open)
    <div class="absolute end-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-border z-50">
        <div class="flex items-center justify-between px-4 py-3 border-b border-border">
            <span class="font-medium text-ink text-sm">{{ __('Notifications') }}</span>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-brand-800 hover:underline">{{ __('Mark all as read') }}</button>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto">
            @forelse ($notifications as $notification)
                <div wire:click="markAsRead('{{ $notification->id }}')"
                    class="px-4 py-3 border-b border-border/60 cursor-pointer hover:bg-app-bg {{ $notification->read_at ? '' : 'bg-gold-300/10' }}">
                    <p class="text-sm text-ink font-medium">{{ $notification->data['title'] ?? '' }}</p>
                    <p class="text-xs text-ink-muted mt-0.5">{{ $notification->data['body'] ?? '' }}</p>
                    <p class="text-[11px] text-ink-muted/70 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-center text-ink-muted text-sm py-8">{{ __('No notifications') }}</p>
            @endforelse
        </div>
        <a href="{{ route('notifications.index') }}" class="block text-center text-xs text-brand-800 py-2 hover:bg-app-bg">
            {{ __('View all') }}
        </a>
    </div>
    @endif
</div>
