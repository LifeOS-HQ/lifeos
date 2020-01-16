<?php

namespace App\Console\Commands\Models;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;

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
        ]);
    }
}
