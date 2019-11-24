<?php
namespace Modules\Roles\Repositories;

use Modules\Roles\Repositories\RoleInterface;
use Modules\Roles\Entities\Role;
use Modules\Core\Repositories\RepositoriesAbstract;

class RoleRepository extends RepositoriesAbstract implements RoleInterface {

    public function __construct(Role $model)
    {
        $this->model    = $model;
    }

    public function findRoleByName($name) {
        return $this->model->findByName($name);
    }

}
