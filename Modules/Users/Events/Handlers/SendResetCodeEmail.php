<?php namespace Modules\Users\Events\Handlers;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Modules\Users\Events\UserHasBegunResetProcess;

class SendResetCodeEmail
{
    public function handle(UserHasBegunResetProcess $event)
    {
        $user = $event->user;
        $code = $event->code;

        Mail::queue('users::emails.reminder', compact('user', 'code'), function (Message $m) use ($user) {
            $m->from('noreply@nairacurrency.com','Naira Currency');
            $m->to($user->email)->subject('Reset your account password.');
        });
    }
}
