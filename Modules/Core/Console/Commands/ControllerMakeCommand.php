<?php

namespace Modules\Core\Console\Commands;

use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Commands\ControllerMakeCommand as GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getControllerName(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'SINGULAR_MODULENAME' => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    private function getStubName()
    {

        if ($this->option('public') === true) return '/controller-public.stub';

        return '/controller.stub';
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            // ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
            // ['api', 'a', InputOption::VALUE_NONE, 'Generate an api controller', null],
            ['public', 'pub', InputOption::VALUE_NONE, 'Generate an api controller', null],
        ];
    }

}
