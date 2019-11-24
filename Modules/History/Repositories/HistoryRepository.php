<?php

namespace Modules\History\Repositories;

use Modules\Core\Repositories\RepositoriesAbstract;
use Modules\History\Entities\History as Model;
use Modules\History\Repositories\HistoryInterface;

class HistoryRepository extends RepositoriesAbstract implements HistoryInterface {

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}
