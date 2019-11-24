<?php

namespace Modules\Users\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Users\Entities\User;

class WelcomeEmail extends Mailable {

    use Queueable, SerializesModels;
    /**
     * @var UserInterface
     */
    public $user;
    /**
     * @var
     */
    public $activationCode;

    /**
     * Create a new message instance.
     *
     * @param UserInterface $user
     * @param $activationCode
     */
    public function __construct(User $user, $activationCode)
    {
        $this->user = $user;
        $this->activationCode = $activationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from_address = config('pennylender.mail_from_address');
        $from_name = config('pennylender.mail_from_name');
        if ($this->user->hasRole('customer'))
            $view = 'users::emails.welcome_customer';
        else
            $view = 'users::emails.welcome';

        return $this->from($from_address, $from_name)
                ->view($view)
                ->subject('Welcome to ' . $from_name);
    }
}
