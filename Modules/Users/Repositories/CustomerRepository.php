<?php
namespace Modules\Users\Repositories;

use Modules\Users\Repositories\AuthInterface;
use Modules\Users\Repositories\UserInterface;
use Modules\Users\Entities\User;
use Modules\Users\Entities\UserDetail;
use Modules\Core\Repositories\RepositoriesAbstract;
use Modules\Users\Entities\Customer;

class CustomerRepository extends RepositoriesAbstract implements CustomerInterface {

    public function __construct(Customer $model)
    {
        $this->model    = $model;
    }


}
