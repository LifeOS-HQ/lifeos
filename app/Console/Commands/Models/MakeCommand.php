<?php

namespace App\Console\Commands\Models;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeCommand extends ModelMakeCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        $this->call('make:test', [
            'name' => $this->argument('name') . 'Test',
            '--unit' => true,
        ]);

        if ($this->option('all')) {

            $modelName = $this->getModelName($this->argument('name'));

            $this->call('make:policy', [
                'name' => $modelName . 'Policy',
                '--model' => $this->argument('name'),
            ]);

        }
    }



    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $replace = [
            'DummyClass' => $class,
            'DummyModelVariable' => lcfirst(class_basename($class)),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }

    protected function getModelName(string $name) : string
    {
        return str_replace('Models\\', '', $name);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $modelName = $this->getModelName($this->argument('name'));

        $controller = Str::studly($modelName);

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:controller', [
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') ? $modelName : null,
            '--parent' => $this->option('parent'),
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('pivot')) {
            return resource_path('/stubs/pivot.model.stub');
        }

        return resource_path('/stubs/model.stub');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, and resource controller for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder file for the model'],
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['parent', 'x', InputOption::VALUE_OPTIONAL, 'parent for controller'],
        ];
    }
}
