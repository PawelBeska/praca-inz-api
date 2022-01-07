<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification implements ShouldQueue
{
    use Queueable;

    public $token;

    public $email;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param $email
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting(__('mail.greeting'))
            ->salutation(__('mail.salutation'))
            ->subject(__('mail.reset_password.subject'))
            ->line(__('mail.reset_password.line_1'))
            ->action(__('mail.reset_password.action'), 'url/reset/' . $this->token . '?email=' . $this->email)
            ->line(__('mail.reset_password.line_2'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
