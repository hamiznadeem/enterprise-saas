<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class TenantPasswordResetNotification extends ResetPasswordNotification implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable)
    {
        // Generate the full reset URL
        $resetUrl = url(config('app.url') . route('tenant.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Reset Your saasPOS Password')
            ->view('emails.tenant-password-reset', [
                'name' => $notifiable->name,
                'resetUrl' => $resetUrl,
            ]);
    }
}