<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $token,
        public User $user
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Kích hoạt tài khoản Karate-Do Shop')
            ->view('emails.kich_hoat');
    }
}
