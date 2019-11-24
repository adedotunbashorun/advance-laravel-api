<?php

namespace Modules\Users\Repositories;

interface AuthInterface{

    public function create(array $data);

    public function check();

    public function hasAccess($permission);

    public function createActivation($user);

    public function activate($userId, $code);

    public function assignRole($user, $role);
}
