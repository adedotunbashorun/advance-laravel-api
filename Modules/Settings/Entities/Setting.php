<?php namespace Modules\Settings\Entities;

use Modules\Core\Entities\Base;
use Modules\History\Traits\Historable;

class Setting extends Base {

    use Historable;

    protected $guarded = ['_token','exit'];

    public $attachments = ['image'];

}
