<?php namespace Modules\Users\Events;

class UserHasRegistered
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
