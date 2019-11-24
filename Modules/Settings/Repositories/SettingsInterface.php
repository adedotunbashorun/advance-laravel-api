<?php namespace Modules\Settings\Repositories;

interface SettingsInterface
{
    public function all();

    public function store(array $data);

    public function deleteImage();

    public function allToArray();
}
