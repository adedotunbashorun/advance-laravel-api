<?php

namespace Modules\Users\Repositories;

interface CustomerInterface{

    public function create(array $data);

    public function update(array $data);
}
