<?php namespace Modules\Users\Events\Handlers;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Modules\Users\Emails\OrderRequested;
use Modules\Users\Emails\WelcomeEmail;
use Modules\Users\Events\UserHasRegistered;
use Modules\Users\Repositories\AuthInterface;

class SendRegistrationConfirmationEmail
{
    /**
     * @var AuthenticationInterface
     */
    private $auth;
    /**
     * @var Mailer
     */
    private $mail;

    /**
     * SendRegistrationConfirmationEmail constructor.
     * @param AuthenticationInterface $auth
     * @param Mailer $mail
     */
    public function __construct(AuthInterface $auth, Mailer $mail)
    {
        $this->auth = $auth;
        $this->mail = $mail;
    }

    /**
     * @param UserHasRegistered $event
     */
    public function handle(UserHasRegistered $event)
    {
        $user = $event->user;

        $activationCode = $this->auth->createActivation($user);
        $this->mail->to($user->email)->send(new WelcomeEmail($user, $activationCode));
    }
}
