<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Public property to hold user data

    public function __construct($user)
    {
        $this->user = $user; // Assign user data to the public property
    }

    public function build()
    {
        return $this->view('emails.verify_email') // Blade view path
                    ->subject('Email Verification');
    }
}
