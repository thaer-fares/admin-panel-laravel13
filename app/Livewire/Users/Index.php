<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // فلاتر وبحث
    public string $search = '';
    public string $roleFilter = '';
    public string $statusFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // نموذج الإنشاء / التعديل
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public bool $is_active = true;
    public array $selectedRoles = [];

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    // حماية: بس مستخدم عنده دور Admin يقدر يعدّل/يوقف/يحذف مستخدم آخر عنده دور Admin
    protected function isProtectedFromActor(User $target): bool
    {
        return $target->hasRole('Admin') && !auth()->user()->hasRole('Admin');
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $this->editingId,
            'password' => $this->editingId ? 'nullable|min:8' : 'required|min:8',
            'is_active' => 'boolean',
            'selectedRoles' => 'array',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'roleFilter', 'statusFilter']);
    }

    public function openCreateModal()
    {
        $this->authorize('manage-users');
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id)
    {
        $this->authorize('manage-users');
        $user = User::findOrFail($id);

        if ($this->isProtectedFromActor($user)) {
            session()->flash('error', __('Only an Admin can modify another Admin account.'));
            return;
        }

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->is_active = $user->is_active;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'name', 'email', 'password', 'selectedRoles']);
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->authorize('manage-users');

        if ($this->editingId) {
            $existing = User::findOrFail($this->editingId);
            if ($this->isProtectedFromActor($existing)) {
                session()->flash('error', __('Only an Admin can modify another Admin account.'));
                $this->showModal = false;
                return;
            }
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];

        if ($this->password !== '') {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
        } else {
            $data['password'] = Hash::make($this->password);
            $user = User::create($data);
        }

        $user->syncRoles($this->selectedRoles);

        $mailFailed = !$this->editingId && !$this->sendNewUserNotifications($user);

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', __('Saved successfully.'));

        if ($mailFailed) {
            session()->flash('error', __('The user was saved, but the email could not be sent. Check the mail settings (MAIL_*) in .env.'));
        }
    }

    // إيميل ترحيب للمستخدم الجديد + إشعار كل المدراء
    // يرجّع false إذا فشل الإرسال (مثلاً إعدادات SMTP غلط) بدون ما يوقف الحفظ
    protected function sendNewUserNotifications(User $user): bool
    {
        $ok = $this->safeNotify($user, new SystemNotification(
            title: 'Welcome to the control panel',
            body: 'Your account has been created. You can sign in using this email address.',
            url: route('login'),
        ));

        User::role('Admin')->whereKeyNot($user->id)->get()->each(function ($admin) use ($user, &$ok) {
            $ok = $this->safeNotify($admin, new SystemNotification(
                title: 'New user created',
                body: $user->name . ' - ' . $user->email,
                url: route('users.index'),
            )) && $ok;
        });

        return $ok;
    }

    protected function safeNotify(User $recipient, SystemNotification $notification): bool
    {
        try {
            $recipient->notify($notification);

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    public function toggleActive(int $id)
    {
        $this->authorize('manage-users');
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', __("You can't deactivate your own account."));
            return;
        }

        if ($this->isProtectedFromActor($user)) {
            session()->flash('error', __('Only an Admin can modify another Admin account.'));
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
    }

    public function confirmDelete(int $id)
    {
        $this->authorize('manage-users');
        $user = User::findOrFail($id);

        if ($this->isProtectedFromActor($user)) {
            session()->flash('error', __('Only an Admin can modify another Admin account.'));
            return;
        }

        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $this->authorize('manage-users');

        if ($this->deletingId === auth()->id()) {
            session()->flash('error', __("You can't delete your own account."));
            $this->showDeleteModal = false;
            return;
        }

        $target = User::findOrFail($this->deletingId);

        if ($this->isProtectedFromActor($target)) {
            session()->flash('error', __('Only an Admin can modify another Admin account.'));
            $this->showDeleteModal = false;
            return;
        }

        $target->delete();
        $this->showDeleteModal = false;
        $this->deletingId = null;
        session()->flash('success', __('Deleted successfully.'));
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.users.index', [
            'users' => $users,
            'roles' => Role::pluck('name'),
        ]);
    }
}
