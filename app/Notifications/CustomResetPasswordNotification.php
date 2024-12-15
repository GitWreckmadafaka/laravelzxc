<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends Notification
{
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
            ->subject('Your Custom Password Reset Link')
            ->line('We received a request to reset your password.')
            ->line('Click the button below to reset your password.')
            ->action('Reset Password', url(route('password.reset', ['token' => $this->token])))
            ->line('If you did not request a password reset, no further action is required.')
            ->view('emails.custom_reset', ['token' => $this->token, 'email' => $notifiable->email]);
    }
}
