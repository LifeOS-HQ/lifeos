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
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $modelName = str_replace('Models\\', '', $this->argument('name'));

        $controller = Str::studly($modelName);

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:controller', [
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') ? $modelName : null,
        ]);
    }
}
