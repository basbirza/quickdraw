<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $token;
    public $resetUrl;

    public function __construct($name, $email, $token)
    {
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
        $this->resetUrl = 'http://localhost:8001/reset-password.html?token=' . $token . '&email=' . urlencode($email);
    }

    public function build()
    {
        return $this->subject('Password Reset Request - Quickdraw Pressing Co.')
                    ->view('emails.password-reset');
    }
}
