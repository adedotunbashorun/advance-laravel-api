<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\History\Traits\Historable;

class UserDetail extends Model
{
    use Historable;

    protected $fillable = [];
}
