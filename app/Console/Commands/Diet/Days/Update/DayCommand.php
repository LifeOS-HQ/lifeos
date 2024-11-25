<?php

namespace App\Console\Commands\Diet\Days\Update;

use App\Models\Diet\Diary\Day;
use Illuminate\Console\Command;

class DayCommand extends Command
{
    protected $signature = 'diet:days:update:day';

    protected $description = 'Sets the day_id for all diet days.';

    public function handle()
    {
        $days = Day::query()
            ->whereNull('day_id')
            ->cursor();

        foreach ($days as $key => $day) {
            $day->setDayId()
                ->save();
            $this->line($key . "\t" . $day->id . "\t" . $day->day_id . "\t" . $day->at);
        }

        return self::SUCCESS;
    }
}
