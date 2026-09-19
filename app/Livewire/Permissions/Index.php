<?php

namespace App\Livewire\Permissions;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public string $name = '';

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount()
    {
        $this->authorize('manage-permissions');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['name']);
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:2|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->name]);

        $this->showModal = false;
        $this->reset(['name']);
        session()->flash('success', __('Saved successfully.'));
    }

    public function confirmDelete(int $id)
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Permission::findOrFail($this->deletingId)->delete();
        $this->showDeleteModal = false;
        session()->flash('success', __('Deleted successfully.'));
    }

    public function render()
    {
        $permissions = Permission::query()
            ->withCount('roles')
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.permissions.index', compact('permissions'));
    }
}
