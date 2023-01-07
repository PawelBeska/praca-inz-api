<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $token,
        public string $email
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->greeting(__('mail.greeting'))
            ->salutation(__('mail.salutation'))
            ->subject(__('mail.reset_password.subject'))
            ->line(__('mail.reset_password.line_1'))
            ->action(__('mail.reset_password.action'), 'url/reset/'.$this->token.'?email='.$this->email)
            ->line(__('mail.reset_password.line_2'));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
