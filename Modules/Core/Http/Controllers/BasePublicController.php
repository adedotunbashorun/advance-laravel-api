<?php namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Core\Traits\RedirectingTrait;
use Kris\LaravelFormBuilder\FormBuilderTrait;


abstract class BasePublicController extends Controller {

    use FormBuilderTrait, RedirectingTrait;

    protected $repository, $states, $types;

    public function __construct($repository = null)
    {
        $this->repository = $repository;
    }


}