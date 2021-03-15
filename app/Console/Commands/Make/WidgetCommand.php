<?php

namespace App\Console\Commands\Make;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class WidgetCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:widget {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Widget (Vue File & Controller)';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        $stub = file_get_contents(resource_path('stubs/vue.widget.stub'));
        $path = resource_path('js/components/widgets/' . $this->getVueFilename($name));
        $controller_class_name = 'Widgets\\' . $this->argument('name') . 'Controller';

        if (file_exists($path)) {
            $this->error('Vue file already exists!');

            return Command::FAILURE;
        }

        $this->makeDirectory($path);
        file_put_contents($path, $stub);
        $this->info('Created Vue file ' . substr($path, strpos($path, 'widgets/')));

        $this->call('make:controller', [
            'name'  => $controller_class_name,
        ]);

        return 0;
    }

    protected function getVueFilename(string $name) : string {

        $parts = explode('\\', $name);
        foreach ($parts as $key => $part) {
            $parts[$key] = Str::singular($part);
        }
        $parts = array_unique($parts);

        return strtolower(implode('/', $parts)) . '.vue';

    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }
}
