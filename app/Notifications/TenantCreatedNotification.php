<?php

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TenantCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Tenant $tenant,
        public array $credentials
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $loginUrl = 'https://' . $this->tenant->web_access_url . '/login';

        return (new MailMessage)
            ->subject('Your saasPOS Account is Ready!')
            ->view('emails.tenant-created', [
                'name'        => $this->credentials['email'],
                'tenantName'  => $this->tenant->name,
                'domain'      => $this->tenant->web_access_url,
                'email'       => $this->credentials['email'],
                'password'    => $this->credentials['password'],
                'loginUrl'    => $loginUrl,
            ]);
    }
}