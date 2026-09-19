<?php

namespace App\Livewire\Notifications;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class NotificationsIndex extends Component
{
    use WithPagination;

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);

        return view('livewire.notifications.notifications-index', compact('notifications'));
    }
}
