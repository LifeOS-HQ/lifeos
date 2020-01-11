<?php

namespace App\Console\Commands\Work\Time;

use App\Models\Work\Month;
use App\Models\Work\Time;
use App\Models\Work\Year;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:time:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports working times from csv file';

    protected $userId = 1;

    protected $year;
    protected $years = [];
    protected $month;

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
        $filename = 'betriko_arbeitszeit.csv';
        $row_count = 0;
        $file = fopen(storage_path('app/' . $filename), "r");
        while (($data = fgetcsv($file, 2000, ";")) !== FALSE) {
            if ($row_count == 0) {
                $row_count++;
                continue;
            }

            $time = Time::createFromCsv($this->userId, $this->getMonth(new Carbon($data[2])), $data);
            $row_count++;
        }

        foreach ($this->years as $year) {
            $year->cacheMonths()
                ->cache()
                ->save();
        }
    }

    protected function getYear(Carbon $startAt) : Year
    {
        if (! is_null($this->year) && $startAt->year == $this->year->year) {
            return $this->year;
        }

        $attributes = [
            'user_id' => $this->userId,
            'year' => $startAt->year,
        ];

        $values = [
            'date' => $startAt->startOfYear(),
        ];

        $this->year = Year::firstOrCreate($attributes, $values);
        $this->years[] = $this->year;

        return $this->year;
    }

    protected function getMonth(Carbon $startAt) : Month
    {
        if (! is_null($this->month) && $startAt->month == $this->month->month) {
            return $this->month;
        }

        $attributes = [
            'user_id' => $this->userId,
            'month' => $startAt->month,
        ];

        $values = [
            'date' => $startAt->startOfMonth(),
        ];

        $this->month = $this->getYear($startAt)->months()->firstOrCreate($attributes, $values);

        return $this->month;
    }
}
