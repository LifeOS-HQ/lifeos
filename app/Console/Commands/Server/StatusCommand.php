<?php

namespace App\Console\Commands\Server;

use App\Support\Servers\Uptime;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class StatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // uptime
        $uptime = $this->getUptime();
        dump($uptime->toArray());
    }

    protected function getUptime() : Uptime
    {
        $uptimeProcess = new Process('uptime');
        $result = $uptimeProcess->run();

        return Uptime::createFromString($uptimeProcess->getOutput());
    }
}
