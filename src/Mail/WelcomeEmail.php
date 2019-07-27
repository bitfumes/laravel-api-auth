<?php

namespace Bitfumes\ApiAuth\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Bitfumes\ApiAuth\Contract\HasApiAuth;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(HasApiAuth $user, $password)
    {
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('api-auth::emails.welcome');
    }
}
