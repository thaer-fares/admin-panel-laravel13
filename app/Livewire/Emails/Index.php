<?php

namespace App\Livewire\Emails;

use App\Mail\AdminMessageMail;
use App\Models\SentEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // لمين بدنا نبعت: all = كل المستخدمين النشطين، role = حسب الدور، users = مستخدمين محددين
    public string $audience = 'all';
    public string $audienceRole = '';
    public array $selectedUsers = [];
    public string $userSearch = '';

    public string $subject = '';
    public string $body = '';

    protected function rules(): array
    {
        return [
            'audience' => 'required|in:all,role,users',
            'audienceRole' => 'required_if:audience,role|nullable|exists:roles,name',
            'selectedUsers' => 'required_if:audience,users|array',
            'selectedUsers.*' => 'integer|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:10000',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'audienceRole' => __('Role'),
            'selectedUsers' => __('Recipients'),
            'subject' => __('Subject'),
            'body' => __('Message'),
        ];
    }

    protected function recipientsQuery()
    {
        return User::query()
            ->where('is_active', true)
            ->when($this->audience === 'role', fn ($q) => $q->whereHas('roles', fn ($r) => $r->where('name', $this->audienceRole)))
            ->when($this->audience === 'users', fn ($q) => $q->whereIn('id', $this->selectedUsers));
    }

    public function send()
    {
        $this->authorize('send-emails');
        $this->validate();

        $recipients = $this->recipientsQuery()->get();

        if ($recipients->isEmpty()) {
            $this->addError('audience', __('No active users match the selected recipients.'));
            return;
        }

        $failed = 0;
        $lastError = null;

        foreach ($recipients as $user) {
            try {
                Mail::to($user)
                    ->locale($user->preferredLocale())
                    ->send(new AdminMessageMail($user, $this->subject, $this->body));
            } catch (\Throwable $e) {
                report($e);
                $failed++;
                $lastError = $e->getMessage();
            }
        }

        SentEmail::create([
            'sender_id' => auth()->id(),
            'subject' => $this->subject,
            'body' => $this->body,
            'audience' => $this->audience,
            'audience_role' => $this->audience === 'role' ? $this->audienceRole : null,
            'recipients_count' => $recipients->count(),
            'failed_count' => $failed,
        ]);

        $sent = $recipients->count() - $failed;

        if ($sent > 0) {
            session()->flash('success', __('Email sent to :count recipient(s).', ['count' => $sent]));
            $this->reset(['subject', 'body', 'selectedUsers', 'userSearch']);
        }

        if ($failed > 0) {
            session()->flash('error', __(':count email(s) could not be sent. Check the mail settings (MAIL_*) in .env.', ['count' => $failed])
                . ($lastError ? ' (' . $lastError . ')' : ''));
        }

        $this->resetPage();
    }

    public function render()
    {
        $searchResults = $this->audience === 'users'
            ? User::query()
                ->where('is_active', true)
                ->when($this->userSearch, function ($q) {
                    $q->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->userSearch . '%')
                          ->orWhere('email', 'like', '%' . $this->userSearch . '%');
                    });
                })
                ->orderBy('name')
                ->limit(20)
                ->get(['id', 'name', 'email'])
            : collect();

        return view('livewire.emails.index', [
            'roles' => Role::pluck('name'),
            'searchResults' => $searchResults,
            'recipientsCount' => $this->recipientsQuery()->count(),
            'history' => SentEmail::with('sender')->latest()->paginate(10),
        ]);
    }
}
