<?php

namespace App\Livewire\Notifications;

use Livewire\Attributes\On;
use Livewire\Component;

class Bell extends Component
{
    public bool $open = false;

    #[On('notification-received')]
    public function refresh() {}

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function markAsRead(string $id)
    {
        $notification = auth()->user()->unreadNotifications()->find($id);
        $notification?->markAsRead();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()->latest()->limit(6)->get();
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('livewire.notifications.bell', compact('notifications', 'unreadCount'));
    }
}
