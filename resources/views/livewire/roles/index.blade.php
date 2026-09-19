<div>
    <x-page-header :title="__('Roles')">
        <x-slot:action>
            <x-button wire:click="openCreateModal">+ {{ __('Create Role') }}</x-button>
        </x-slot:action>
    </x-page-header>

    @if (session('success'))
        <div class="bg-success/10 text-success text-sm px-4 py-2 rounded-xl mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-danger/10 text-danger text-sm px-4 py-2 rounded-xl mb-4">{{ session('error') }}</div>
    @endif

    <x-card class="mb-4">
        <input type="text" wire:model.live.debounce.400ms="search"
            placeholder="{{ __('Search by role name...') }}"
            class="w-full sm:w-80 rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
    </x-card>

    <x-card padding="p-0" class="overflow-hidden">
        <table class="w-full text-sm text-start">
            <thead class="bg-app-bg text-ink-muted">
                <tr>
                    <th class="px-4 py-3 text-start">{{ __('Role') }}</th>
                    <th class="px-4 py-3 text-start">{{ __('Permissions') }}</th>
                    <th class="px-4 py-3 text-start">{{ __('Users count') }}</th>
                    <th class="px-4 py-3 text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($roles as $role)
                    <tr wire:key="role-{{ $role->id }}" class="hover:bg-app-bg/60">
                        <td class="px-4 py-3 font-medium text-ink">{{ $role->name }}</td>
                        <td class="px-4 py-3">
                            @forelse ($role->permissions as $permission)
                                <x-badge variant="gold" class="me-1 mb-1">{{ $permission->name }}</x-badge>
                            @empty
                                <span class="text-ink-muted text-xs">{{ __('No permissions') }}</span>
                            @endforelse
                        </td>
                        <td class="px-4 py-3 text-ink-muted">{{ $role->users_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <button wire:click="openEditModal({{ $role->id }})" class="text-brand-800 hover:underline text-xs font-medium">{{ __('Edit') }}</button>
                                @if ($role->name !== 'Admin')
                                <button wire:click="confirmDelete({{ $role->id }})" class="text-danger hover:underline text-xs font-medium">{{ __('Delete') }}</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-ink-muted">{{ __('No results found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">{{ $roles->links() }}</div>

    @if ($showModal)
    <div class="fixed inset-0 bg-brand-950/50 flex items-center justify-center z-50 p-4" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-ink mb-4">
                {{ $editingId ? __('Edit Role') : __('Create Role') }}
            </h2>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm text-ink-muted mb-1">{{ __('Role name') }}</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
                    @error('name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-ink-muted mb-1">{{ __('Permissions') }}</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-1.5 text-sm bg-app-bg px-2.5 py-1.5 rounded-lg">
                                <input type="checkbox" value="{{ $permission }}" wire:model="selectedPermissions" class="rounded border-border text-brand-800">
                                {{ $permission }}
                            </label>
                        @endforeach
                    </div>
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
            <p class="text-ink mb-5">{{ __('Are you sure you want to delete this role?') }}</p>
            <div class="flex justify-center gap-2">
                <x-button variant="ghost" wire:click="$set('showDeleteModal', false)">{{ __('Cancel') }}</x-button>
                <x-button variant="danger" wire:click="delete">{{ __('Delete') }}</x-button>
            </div>
        </div>
    </div>
    @endif
</div>
