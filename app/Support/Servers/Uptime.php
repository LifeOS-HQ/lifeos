<?php

namespace App\Support\Servers;

use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;

class Uptime
{
    const CACHE_SECONDS = 300;

    const INDEX_TIME = 0;
    const INDEX_UPTIME_VALUE = 2;
    const INDEX_UPTIME_UNIT = 3;
    const INDEX_USERS = 5;
    const INDEX_LOAD_1 = 9;
    const INDEX_LOAD_5 = 10;
    const INDEX_LOAD_15 = 11;

    public $time;
    public $uptime;
    public $load;
    public $users;

    public static function createFromString(string $output) : self
    {
        $uptime = new self();
        $uptime->parseOutput($output);

        return $uptime;
    }

    public static function get() : self
    {
        return Cache::remember('home.server', self::CACHE_SECONDS, function () {
            return self::run();
        });
    }

    public static function run() : self
    {
        $uptimeProcess = new Process('uptime');
        $result = $uptimeProcess->run();

        return self::createFromString($uptimeProcess->getOutput());
    }

    public function __construct()
    {

    }

    public function parseOutput(string $output) : self
    {
        $output = str_replace('  ', ' ', $output);
        $parts = explode(" ", trim($output));

        $this->time = $parts[self::INDEX_TIME];
        $this->uptime = $this->setUptime($parts);
        $this->load = $this->setLoad($parts);
        $this->users = $parts[self::INDEX_USERS];

        return $this;
    }

    protected function setUptime(array $parts) : array
    {
        $value = $parts[self::INDEX_UPTIME_VALUE];
        $unit = trim($parts[self::INDEX_UPTIME_UNIT], ',');

        return [
            'value' => $value,
            'unit' => $unit,
            'formatted' => $value . ' ' . $unit,
        ];
    }

    protected function setLoad(array $parts) : array
    {
        return [
            1 => $this->toFloat($parts[self::INDEX_LOAD_1]),
            5 => $this->toFloat($parts[self::INDEX_LOAD_5]),
            15 => $this->toFloat($parts[self::INDEX_LOAD_15]),
        ];
    }

    protected function toFloat(string $formatted) : float
    {
        return (float) str_replace(',' ,'.', $formatted);
    }

    public function toArray() : array
    {
        return [
            'time' => $this->time,
            'uptime' => $this->uptime,
            'load' => $this->load,
            'users' => $this->users,
        ];
    }
}