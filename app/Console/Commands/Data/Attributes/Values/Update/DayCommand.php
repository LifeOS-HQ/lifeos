<?php

namespace App\Console\Commands\Data\Attributes\Values\Update;

use Illuminate\Console\Command;
use App\Models\Services\Data\Value;

class DayCommand extends Command
{
    protected $signature = 'data:attributes:values:update:day';

    protected $description = 'Sets the day_id for all data values.';

    public function handle()
    {
        $values = Value::query()
            ->whereNull('day_id')
            ->cursor();

        foreach ($values as $key => $value) {
            $value->setDayId()
                ->save();
            $this->line($key . "\t" . $value->id . "\t" . $value->day_id . "\t" . $value->at);
        }

        return self::SUCCESS;
    }
}
