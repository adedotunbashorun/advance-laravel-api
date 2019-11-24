<?php namespace Modules\Users\Services;

use Modules\Users\Events\UserHasRegistered;
use Modules\Users\Repositories\AuthInterface;

class UserRegistration
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var RoleRepository
     */
    private $role;
    /**
     * @var array
     */
    private $input;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function register(array $input)
    {
        $this->input = $input;
        $user = $this->createUser();
        $group = !empty($this->input['roles']) ? $this->input['roles'] : 'customer';
        $this->assignUserToGroup($user, $group);

        event(new UserHasRegistered($user));

        return $user;
    }

    private function createUser()
    {
        return $this->auth->create((array)$this->input);
    }

    private function assignUserToGroup($user, $group = 'customer')
    {
        $this->auth->assignRole($user, $group);
    }
}
