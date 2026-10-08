<div>
    <x-page-header :title="__('Send Emails')" />

    @if (session('success'))
        <div class="bg-success/10 text-success text-sm px-4 py-2 rounded-xl mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-danger/10 text-danger text-sm px-4 py-2 rounded-xl mb-4">{{ session('error') }}</div>
    @endif

    <x-card class="mb-6">
        <form wire:submit="send" class="space-y-4">
            {{-- المستلمين --}}
            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('Recipients') }}</label>
                <div class="flex flex-wrap gap-4 text-sm text-ink">
                    <label class="flex items-center gap-2">
                        <input type="radio" wire:model.live="audience" value="all" class="text-brand-800 focus:ring-gold-500">
                        {{ __('All active users') }}
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" wire:model.live="audience" value="role" class="text-brand-800 focus:ring-gold-500">
                        {{ __('By role') }}
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" wire:model.live="audience" value="users" class="text-brand-800 focus:ring-gold-500">
                        {{ __('Specific users') }}
                    </label>
                </div>
                @error('audience') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            @if ($audience === 'role')
                <div>
                    <select wire:model.live="audienceRole" class="w-full sm:w-64 rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
                        <option value="">{{ __('Choose a role') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('audienceRole') <span class="block text-danger text-xs">{{ $message }}</span> @enderror
                </div>
            @endif

            @if ($audience === 'users')
                <div>
                    <input type="text" wire:model.live.debounce.400ms="userSearch"
                        placeholder="{{ __('Search by name or email...') }}"
                        class="w-full sm:w-80 rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500 mb-2">
                    <div class="max-h-56 overflow-y-auto border border-border rounded-xl divide-y divide-border">
                        @forelse ($searchResults as $user)
                            <label wire:key="recipient-{{ $user->id }}" class="flex items-center gap-3 px-3 py-2 text-sm hover:bg-app-bg cursor-pointer">
                                <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" class="rounded text-brand-800 focus:ring-gold-500">
                                <span class="text-ink">{{ $user->name }}</span>
                                <span class="text-ink-muted text-xs">{{ $user->email }}</span>
                            </label>
                        @empty
                            <p class="text-center text-ink-muted text-sm py-4">{{ __('No results found.') }}</p>
                        @endforelse
                    </div>
                    @error('selectedUsers') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                </div>
            @endif

            <p class="text-xs text-ink-muted">{{ __('Will be sent to :count recipient(s).', ['count' => $recipientsCount]) }}</p>

            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('Subject') }}</label>
                <input type="text" wire:model="subject" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
                @error('subject') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm text-ink-muted mb-1">{{ __('Message') }}</label>
                <textarea wire:model="body" rows="6" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500"></textarea>
                @error('body') <span class="text-danger text-xs">{{ $message }}</span> @enderror
            </div>

            <x-button type="submit" wire:loading.attr="disabled" wire:target="send">
                <span wire:loading.remove wire:target="send">{{ __('Send') }}</span>
                <span wire:loading wire:target="send">{{ __('Sending...') }}</span>
            </x-button>
        </form>
    </x-card>

    {{-- سجل الإيميلات المرسلة --}}
    <h2 class="text-lg font-bold text-ink mb-3">{{ __('Sent emails') }}</h2>
    <x-card padding="p-0" class="overflow-x-auto">
        <table class="w-full text-sm text-start">
            <thead class="bg-app-bg text-ink-muted">
                <tr>
                    <th class="px-4 py-3 text-start">{{ __('Subject') }}</th>
                    <th class="px-4 py-3 text-start">{{ __('Recipients') }}</th>
                    <th class="px-4 py-3 text-start">{{ __('Sent by') }}</th>
                    <th class="px-4 py-3 text-start">{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($history as $email)
                    <tr wire:key="sent-{{ $email->id }}" class="hover:bg-app-bg/60">
                        <td class="px-4 py-3 text-ink font-medium">{{ $email->subject }}</td>
                        <td class="px-4 py-3">
                            <x-badge variant="brand">
                                @if ($email->audience === 'role') {{ $email->audience_role }}
                                @elseif ($email->audience === 'users') {{ __('Specific users') }}
                                @else {{ __('All active users') }}
                                @endif
                            </x-badge>
                            <span class="text-ink-muted text-xs ms-1">{{ $email->recipients_count }}</span>
                            @if ($email->failed_count > 0)
                                <x-badge variant="danger" class="ms-1">{{ __('Failed') }}: {{ $email->failed_count }}</x-badge>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-ink-muted">{{ $email->sender?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-ink-muted">{{ $email->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-ink-muted">{{ __('No emails sent yet.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">{{ $history->links() }}</div>
</div>
