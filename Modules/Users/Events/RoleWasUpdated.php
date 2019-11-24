<?php namespace Modules\Users\Events;

class RoleWasUpdated
{
    public $role;

    public function __construct($role)
    {
        $this->role = $role;
    }
}
