<?php
namespace Modules\Users\Repositories;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Repositories\AuthInterface;
use Modules\Users\Repositories\UserInterface;
use Modules\Users\Entities\User;
use Modules\Users\Entities\UserDetail;
use Modules\Core\Repositories\RepositoriesAbstract;

class UserRepository extends RepositoriesAbstract implements UserInterface, AuthInterface {

    public function __construct(User $model)
    {
        $this->model    = $model;
    }

    public function check()
    {
        if (Auth::check()) {
            return Auth::user();
        }

        return false;
    }

    public function hasAccess($permission)
    {
        $user = Auth::user();

        if (empty($user)) {
            return false;
        }

        return $user->hasAccess($permission);
    }

    public function createActivation($user)
    {
        $user->activation_code = generate_password();
        $user->save();

        return $user->activation_code;
    }

    public function activate($userId, $code)
    {
        $user = $this->byId($userId);

        // $success = new Exception("This code do not match your activation code!");
        if( $user->activation_code = $code){
            $user->activation_code = null;
            $user->email_verified_at = Carbon::now();
            $success = $user->save();
        }

        // if ($success) {
        //     event(new UserHasActivatedAccount($user));
        // }

        return $success;
    }

    public function assignRole($user, $role)
    {
        return $user->assignRole($role);
    }
}
