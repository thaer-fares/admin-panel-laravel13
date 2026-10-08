<?php

namespace App\Livewire\Profile;

use App\Notifications\SystemNotification;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class UpdateProfile extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
        $this->phone = auth()->user()->phone ?? '';
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('success', __('Profile updated successfully.'));
    }

    // زر لفحص إعدادات البريد: يبعت إيميل تجريبي لإيميل المستخدم الحالي
    public function sendTestEmail()
    {
        $user = auth()->user();

        try {
            $user->notify(new SystemNotification(
                title: 'Test email',
                body: 'If you received this message, email sending is working correctly.',
            ));

            session()->flash('success', __('Test email sent to :email', ['email' => $user->email]));
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', __('Email could not be sent. Check the mail settings (MAIL_*) in .env.') . ' (' . $e->getMessage() . ')');
        }
    }

    public function render()
    {
        return view('livewire.profile.update-profile');
    }
}
