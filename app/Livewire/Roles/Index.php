<?php

namespace App\Livewire\Roles;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public array $selectedPermissions = [];

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount()
    {
        $this->authorize('manage-roles');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id)
    {
        $role = Role::findOrFail($id);
        $this->editingId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'name', 'selectedPermissions']);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:2|max:255|unique:roles,name,' . $this->editingId,
            'selectedPermissions' => 'array',
        ]);

        $role = $this->editingId
            ? Role::findOrFail($this->editingId)
            : Role::create(['name' => $this->name]);

        if ($this->editingId) {
            $role->update(['name' => $this->name]);
        }

        $role->syncPermissions($this->selectedPermissions);

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', __('Saved successfully.'));
    }

    public function confirmDelete(int $id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $role = Role::findOrFail($this->deletingId);

        if (in_array($role->name, ['Admin'])) {
            session()->flash('error', __('This role cannot be deleted.'));
            $this->showDeleteModal = false;
            return;
        }

        $role->delete();
        $this->showDeleteModal = false;
        session()->flash('success', __('Deleted successfully.'));
    }

    public function render()
    {
        $roles = Role::query()
            ->with('permissions')
            ->withCount('users')
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.roles.index', [
            'roles' => $roles,
            'permissions' => Permission::pluck('name'),
        ]);
    }
}
