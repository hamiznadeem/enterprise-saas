<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TwoFactorOTPNotification extends Notification
{
    use Queueable;

    public function __construct(public string $code) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your 2FA Code — POS')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your two-factor authentication code is:')
            ->line('**' . $this->code . '**')
            ->line('This code expires in 5 minutes.')
            ->line('If you did not request this, ignore this email.')
            ->salutation('Regards, POS Team');
    }
}