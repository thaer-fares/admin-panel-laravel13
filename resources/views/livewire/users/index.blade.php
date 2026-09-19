<div>
    <x-page-header :title="__('Users')">
        <x-slot:action>
            <div class="flex items-center gap-2">
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <x-button variant="secondary" type="button" @click="open = !open">
                        {{ __('Export') }}
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                    </x-button>
                    <div x-show="open" x-cloak @click="open = false"
                         class="absolute end-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-border py-1 text-sm z-40">
                        <a href="{{ route('users.export', 'xlsx') }}?{{ http_build_query(['search' => $search, 'role' => $roleFilter, 'status' => $statusFilter]) }}"
                           class="block px-4 py-2 hover:bg-app-bg text-ink" target="_blank">Excel (.xlsx)</a>
                        <a href="{{ route('users.export', 'csv') }}?{{ http_build_query(['search' => $search, 'role' => $roleFilter, 'status' => $statusFilter]) }}"
                           class="block px-4 py-2 hover:bg-app-bg text-ink" target="_blank">CSV</a>
                        <a href="{{ route('users.export', 'pdf') }}?{{ http_build_query(['search' => $search, 'role' => $roleFilter, 'status' => $statusFilter]) }}"
                           class="block px-4 py-2 hover:bg-app-bg text-ink" target="_blank">PDF</a>
                    </div>
                </div>
                @can('manage-users')
                    <x-button wire:click="openCreateModal">+ {{ __('Create User') }}</x-button>
                @endcan
            </div>
        </x-slot:action>
    </x-page-header>

    @if (session('success'))
        <div class="bg-success/10 text-success text-sm px-4 py-2 rounded-xl mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-danger/10 text-danger text-sm px-4 py-2 rounded-xl mb-4">{{ session('error') }}</div>
    @endif

    {{-- شريط البحث والفلاتر --}}
    <x-card class="mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <input type="text" wire:model.live.debounce.400ms="search"
                placeholder="{{ __('Search by name or email...') }}"
                class="sm:col-span-2 rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">

            <select wire:model.live="roleFilter" class="rounded-xl border-border text-sm focus:ring-gold-500 focus:border-gold-500">
                <option value="">{{ __('All roles') }}</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}">{{ $role }}</option>
                @endforeach
            </select>

            <select wire:model.live="statusFilter" class="rounded-xl border-border text-sm focus:ring-gold-500 focus:border-gold-500">
                <option value="">{{ __('All statuses') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="inactive">{{ __('Inactive') }}</option>
            </select>
        </div>
    </x-card>

    <x-card padding="p-0" class="overflow-hidden">
        <table class="w-full text-sm text-start">
            <thead class="bg-app-bg text-ink-muted">
                <tr>
                    <th class="px-4 py-3 text-start cursor-pointer select-none" wire:click="sortBy('name')">
                        {{ __('Name') }}
                        @if($sortField === 'name') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th class="px-4 py-3 text-start cursor-pointer select-none" wire:click="sortBy('email')">
                        {{ __('Email') }}
                        @if($sortField === 'email') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th class="px-4 py-3 text-start">{{ __('Roles') }}</th>
                    <th class="px-4 py-3 text-start">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-start cursor-pointer select-none" wire:click="sortBy('created_at')">
                        {{ __('Created at') }}
                        @if($sortField === 'created_at') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th class="px-4 py-3 text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($users as $user)
                    @php $isProtected = $user->hasRole('Admin') && !auth()->user()->hasRole('Admin'); @endphp
                    <tr wire:key="user-{{ $user->id }}" class="hover:bg-app-bg/60">
                        <td class="px-4 py-3 font-medium text-ink">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-ink-muted">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @foreach ($user->roles as $role)
                                <x-badge variant="brand" class="me-1">{{ $role->name }}</x-badge>
                            @endforeach
                        </td>
                        <td class="px-4 py-3">
                            @if (!$isProtected)
                                @can('manage-users')
                                <button wire:click="toggleActive({{ $user->id }})">
                                    <x-badge :variant="$user->is_active ? 'success' : 'neutral'">
                                        {{ $user->is_active ? __('Active') : __('Inactive') }}
                                    </x-badge>
                                </button>
                                @else
                                    <x-badge :variant="$user->is_active ? 'success' : 'neutral'">
                                        {{ $user->is_active ? __('Active') : __('Inactive') }}
                                    </x-badge>
                                @endcan
                            @else
                                <x-badge :variant="$user->is_active ? 'success' : 'neutral'">
                                    {{ $user->is_active ? __('Active') : __('Inactive') }}
                                </x-badge>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-ink-muted">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            @if ($isProtected)
                                <span class="text-xs text-ink-muted">{{ __('Protected account') }}</span>
                            @else
                                @can('manage-users')
                                <div class="flex justify-end gap-3">
                                    <button wire:click="openEditModal({{ $user->id }})" class="text-brand-800 hover:underline text-xs font-medium">{{ __('Edit') }}</button>
                                    <button wire:click="confirmDelete({{ $user->id }})" class="text-danger hover:underline text-xs font-medium">{{ __('Delete') }}</button>
                                </div>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-ink-muted">{{ __('No results found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">{{ $users->links() }}</div>

    {{-- Modal إنشاء / تعديل --}}
    @if ($showModal)
    <div class="fixed inset-0 bg-brand-950/50 flex items-center justify-center z-50 p-4" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-ink mb-4">
                {{ $editingId ? __('Edit User') : __('Create User') }}
            </h2>
            <form wire:submit="save" class="space-y-4">
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
                    <label class="block text-sm text-ink-muted mb-1">
                        {{ __('Password') }} @if($editingId) <span class="text-ink-muted/70">({{ __('leave empty to keep current') }})</span> @endif
                    </label>
                    <input type="password" wire:model="password" class="w-full rounded-xl border-border text-sm text-start focus:ring-gold-500 focus:border-gold-500">
                    @error('password') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm text-ink-muted mb-1">{{ __('Roles') }}</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($roles as $role)
                            <label class="flex items-center gap-1.5 text-sm bg-app-bg px-2.5 py-1.5 rounded-lg">
                                <input type="checkbox" value="{{ $role }}" wire:model="selectedRoles" class="rounded border-border text-brand-800">
                                {{ $role }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-ink-muted">
                    <input type="checkbox" wire:model="is_active" class="rounded border-border text-brand-800">
                    {{ __('Active') }}
                </label>

                <div class="flex justify-end gap-2 pt-2">
                    <x-button variant="ghost" type="button" wire:click="$set('showModal', false)">{{ __('Cancel') }}</x-button>
                    <x-button type="submit">{{ __('Save') }}</x-button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Modal تأكيد الحذف --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 bg-brand-950/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
            <p class="text-ink mb-5">{{ __('Are you sure you want to delete this user?') }}</p>
            <div class="flex justify-center gap-2">
                <x-button variant="ghost" wire:click="$set('showDeleteModal', false)">{{ __('Cancel') }}</x-button>
                <x-button variant="danger" wire:click="delete">{{ __('Delete') }}</x-button>
            </div>
        </div>
    </div>
    @endif
</div>
