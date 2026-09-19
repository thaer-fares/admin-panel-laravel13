<div>
    <x-page-header :title="__('Permissions')">
        <x-slot:action>
            <x-button wire:click="openCreateModal">+ {{ __('Create Permission') }}</x-button>
        </x-slot:action>
    </x-page-header>

    @if (session('success'))
        <div class="bg-success/10 text-success text-sm px-4 py-2 rounded-xl mb-4">{{ session('success') }}</div>
    @endif

    <x-card class="mb-4">
        <input type="text" wire:model.live.debounce.400ms="search"
            placeholder="{{ __('Search by permission name...') }}"
            class="w-full sm:w-80 rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
    </x-card>

    <x-card padding="p-0" class="overflow-hidden">
        <table class="w-full text-sm text-start">
            <thead class="bg-app-bg text-ink-muted">
                <tr>
                    <th class="px-4 py-3 text-start">{{ __('Permission') }}</th>
                    <th class="px-4 py-3 text-start">{{ __('Used in roles') }}</th>
                    <th class="px-4 py-3 text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($permissions as $permission)
                    <tr wire:key="perm-{{ $permission->id }}" class="hover:bg-app-bg/60">
                        <td class="px-4 py-3 font-medium text-ink">{{ $permission->name }}</td>
                        <td class="px-4 py-3 text-ink-muted">{{ $permission->roles_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <button wire:click="confirmDelete({{ $permission->id }})" class="text-danger hover:underline text-xs font-medium">{{ __('Delete') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-ink-muted">{{ __('No results found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">{{ $permissions->links() }}</div>

    @if ($showModal)
    <div class="fixed inset-0 bg-brand-950/50 flex items-center justify-center z-50 p-4" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
            <h2 class="text-lg font-semibold text-ink mb-4">{{ __('Create Permission') }}</h2>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm text-ink-muted mb-1">{{ __('Permission name') }}</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500" placeholder="manage-invoices">
                    @error('name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <x-button variant="ghost" type="button" wire:click="$set('showModal', false)">{{ __('Cancel') }}</x-button>
                    <x-button type="submit">{{ __('Save') }}</x-button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if ($showDeleteModal)
    <div class="fixed inset-0 bg-brand-950/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
            <p class="text-ink mb-5">{{ __('Are you sure you want to delete this permission?') }}</p>
            <div class="flex justify-center gap-2">
                <x-button variant="ghost" wire:click="$set('showDeleteModal', false)">{{ __('Cancel') }}</x-button>
                <x-button variant="danger" wire:click="delete">{{ __('Delete') }}</x-button>
            </div>
        </div>
    </div>
    @endif
</div>
