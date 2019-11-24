<?php
namespace Modules\Core\Console\Commands;


use Modules\Core\Console\Generators\ModuleGenerator;
use Nwidart\Modules\Commands\ModuleMakeCommand as Command;

class ModuleMakeCommand extends Command
{

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $names = $this->argument('name');

        foreach ($names as $name) {
            with(new ModuleGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setModule($this->laravel['modules'])
                ->setConfig($this->laravel['config'])
                ->setConsole($this)
                ->setForce($this->option('force'))
                ->setPlain($this->option('plain'))
                ->generate();
        }
    }

}
