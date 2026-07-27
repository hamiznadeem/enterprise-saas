<?php

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeTrialNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Tenant $tenant,
        public string $ownerEmail,
        public int $trialDays = 14
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $loginUrl = url('/login');
        $expiryDate = now()->addDays($this->trialDays)->format('d M, Y');

        return (new MailMessage)
            ->subject("Welcome to Enterprise SaaS! Your {$this->trialDays}-Day Free Trial is Active 🎉")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Thank you for registering **{$this->tenant->name}** with Enterprise SaaS POS System.")
            ->line("Your **{$this->trialDays}-Day Free Trial** is now fully active and will expire on **{$expiryDate}**.")
            ->line("**Your Account Details:**")
            ->line("• Business Name: {$this->tenant->name}")
            ->line("• Subdomain / URL: {$this->tenant->domain}")
            ->line("• Login Email: {$this->ownerEmail}")
            ->action('Access Your POS Dashboard', $loginUrl)
            ->line("If you need any help setting up your store, our support team is available 24/7.")
            ->line("Thank you for choosing Enterprise SaaS!");
    }
}
