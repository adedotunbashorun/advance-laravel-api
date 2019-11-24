<?php
namespace Modules\Core\Console\Generators;

use Nwidart\Modules\Generators\ModuleGenerator as Generator;

class ModuleGenerator extends Generator
{
    /**
     * Generate some resources.
     */
    public function generateResources()
    {
        $this->console->call('module:make-seed', [
            'name' => $this->getName(),
            'module' => $this->getName(),
            '--master' => true,
        ]);

        $this->console->call('module:make-provider', [
            'name' => $this->getName() . 'ServiceProvider',
            'module' => $this->getName(),
            '--master' => true,
        ]);

        $this->console->call('module:make-controller', [
            'controller' => $this->getName() . 'Controller',
            'module' => $this->getName(),
        ]);

        /*$this->console->call('module:make-controller', [
            'controller' => $this->getName() . 'AjaxController',
            'module' => $this->getName(),
            '--ajax' => true,
        ]);*/

        $this->console->call('module:make-model', [
            'model' => str_singular($this->getName()),
            'module' => $this->getName(),
            /*'-m' => true,*/
        ]);

        $this->console->call('module:make-repo', [
            'name' => str_singular($this->getName()).'Repository',
            'module' => $this->getName(),
        ]);

        $this->console->call('module:make-repo-interface', [
            'name' => str_singular($this->getName()).'Interface',
            'module' => $this->getName(),
        ]);

        $this->console->call('module:make-facade', [
            'name' => 'Facade',
            'module' => $this->getName(),
        ]);

        $this->console->call('module:make-request', [
            'name' => 'FormRequest',
            'module' => $this->getName(),
        ]);

        $this->console->call('module:make-presenter', [
            'name' => 'ModulePresenter',
            'module' => $this->getName(),
        ]);

        $this->console->call('make:form', [
            'name' => $this->getName().'Form',
            '--fields' => 'name:text',
            '--namespace' => 'Modules\\'.$this->getName().'\\Forms',
            '--path' => 'modules/'.$this->getName().'/Forms',
        ]);

        $this->console->call('module:route-provider', [
            'module' => $this->getName(),
        ]);
    }

    /**
     * Get the module name in lower Singular case.
     *
     * @return string
     */
    protected function getSingularLowerNameReplacement()
    {
        return str_singular($this->getLowerNameReplacement());
    }

    /**
     * Get the module name in lower Singular case.
     *
     * @return string
     */
    protected function getSingularStudlyNameReplacement()
    {
        return str_singular($this->getStudlyNameReplacement());
    }

}
