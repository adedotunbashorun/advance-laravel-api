<?php

namespace Modules\Roles\Repositories;

use Modules\Core\Repositories\RepositoryInterface;

interface RoleInterface extends RepositoryInterface {

    public function create(array $data);

    public function update(array $data);
}
