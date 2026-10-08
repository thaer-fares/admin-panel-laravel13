<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public ?string $url = null,
        public string $icon = 'bell',
        public bool $sendMail = true
    ) {}

    public function via(object $notifiable): array
    {
        // الإشعار يظهر بالجرس داخل اللوحة + يوصل كإيميل للمستخدم
        return $this->sendMail && $notifiable->email
            ? ['database', 'mail']
            : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject(__($this->title))
            ->greeting(__('Hello :name', ['name' => $notifiable->name]))
            ->line(__($this->body));

        if ($this->url) {
            $mail->action(__('Open'), $this->url);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
            'icon' => $this->icon,
        ];
    }
}
