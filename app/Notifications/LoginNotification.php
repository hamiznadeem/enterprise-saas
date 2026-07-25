<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoginNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $ip,
        public string $browser,
        public string $os,
        public string $time
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Login Detected — POS')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new login was detected on your account.')
            ->line('**Time:** ' . $this->time)
            ->line('**IP Address:** ' . $this->ip)
            ->line('**Browser:** ' . $this->browser)
            ->line('**OS:** ' . $this->os)
            ->line('If this was you, no action is needed.')
            ->line('If you did not log in, please change your password immediately and enable 2FA.')
            ->action('Secure Your Account', route('password.change'))
            ->salutation('Regards, POS Security Team');
    }
}