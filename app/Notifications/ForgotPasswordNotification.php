<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordNotification extends Notification
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password')
            ->greeting('Hello!')
            ->line('You requested a password reset.')
            ->line('Your reset token is:')
            ->line($this->token)
            ->line('Use this token to reset your password.')
            ->line('If you did not request this, please ignore this email.');
    }
}