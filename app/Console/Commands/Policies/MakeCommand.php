<?php

namespace App\Console\Commands\Policies;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\PolicyMakeCommand;

class MakeCommand extends PolicyMakeCommand
{
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('model')
                    ? resource_path('stubs/policy.stub')
                    : __DIR__.'/stubs/policy.plain.stub';
    }
}
