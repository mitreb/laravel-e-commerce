<?php

namespace App\Notifications;

use App\Models\DiscountCode;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DiscountCodeGenerated extends Notification
{
    use Queueable;

    public function __construct(
        private DiscountCode $discountCode
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Discount Code is Ready!')
            ->line('Thank you for your order!')
            ->line('Here is your €5 discount code for your next purchase:')
            ->line($this->discountCode->code)
            ->line('This code will expire on ' . $this->discountCode->expires_at->format('d/m/Y'))
            ->action('Shop Now', url('/'))
            ->line('Thank you for shopping with us!');
    }
}
