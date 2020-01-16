<?php

namespace App\Console\Commands\Controller;

use Illuminate\Console\Command;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeCommand extends ControllerMakeCommand
{
    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        parent::handle();

        $this->call('make:test', [
            'name' => 'Controller\\' . $this->argument('name') . 'Test',
            '--model' => $this->option('model') ?: null,
        ]);

        if ($this->option('model')) {
            $name = $this->getViewPath($this->option('model'));

            $this->call('make:view', ['name' => $name . '/index']);
            $this->call('make:view', ['name' => $name . '/show']);
            $this->call('make:view', ['name' => $name . '/edit']);
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;

        if ($this->option('parent')) {
            $stub = '/stubs/controller.nested.stub';
        } elseif ($this->option('model')) {
            $stub = '/stubs/controller.model.stub';
        } elseif ($this->option('invokable')) {
            $stub = '/stubs/controller.invokable.stub';
        } elseif ($this->option('resource')) {
            $stub = '/stubs/controller.stub';
        }

        if ($this->option('api') && is_null($stub)) {
            $stub = '/stubs/controller.api.stub';
        } elseif ($this->option('api') && ! is_null($stub) && ! $this->option('invokable')) {
            $stub = str_replace('.stub', '.api.stub', $stub);
        }

        $stub = $stub ?? '/stubs/controller.plain.stub';

        if ($this->option('model')) {
            return resource_path('stubs/controller.model.stub');
        }

        return __DIR__.$stub;
    }

    protected function getViewPath(string $modelName) : string {

        $modelName = str_replace('Models\\', '', $modelName);
        $modelName = ltrim($modelName, 'App\\');

        $parts = explode('\\', $modelName);
        foreach ($parts as $key => $part) {
            $parts[$key] = Str::singular($part);
        }
        $parts = array_unique($parts);

        return strtolower(implode('/', $parts)) . '/';

    }
}
