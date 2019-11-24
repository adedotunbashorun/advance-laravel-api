<?php namespace Modules\Users\Events;

class UserWasUpdated
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
