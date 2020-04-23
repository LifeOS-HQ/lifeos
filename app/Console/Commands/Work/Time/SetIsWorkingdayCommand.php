<?php

namespace App\Console\Commands\Work\Time;

use App\Models\Work\Time;
use App\Support\Holidays;
use Illuminate\Console\Command;

class SetIsWorkingdayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:time:set_is_workingday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets is_workingday field for all times';

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
        $models = Time::all();
        foreach ($models as $key => $model) {
            $model->is_workingday = Holidays::isWorkingday($model->start_at);
            $model->save();
        }
    }
}
