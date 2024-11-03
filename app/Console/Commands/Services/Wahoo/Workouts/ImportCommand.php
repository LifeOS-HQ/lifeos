<?php

namespace App\Console\Commands\Services\Wahoo\Workouts;

use App\Apis\Wahoo\Wahoo;
use Illuminate\Console\Command;
use App\Models\Services\Service;
use App\Models\Workouts\History;
use App\Models\Workouts\Workout;

class ImportCommand extends Command
{
    protected $signature = 'services:wahoo:workouts:import
        {user : The ID of the user}';

    protected $description = 'Imports all Workouts';

    public function handle()
    {
        $habitica_service = Service::where('slug', 'wahoo')->first();

        $service_user = \App\Models\Services\User::where('user_id', $this->argument('user'))
            ->where('service_id', $habitica_service->id)
            ->first();

        if (is_null($service_user)) {
            $this->error('User not found');

            return self::FAILURE;
        }

        $api = new Wahoo($service_user);

        $response = $api->getWorkouts();

        if ($response->failed()) {
            $this->error('Failed to get tasks');

            return self::FAILURE;
        }

        $workout = Workout::where('user_id', $this->argument('user'))
            ->where('name', 'Zwift 750 kcal')
            ->first();

        $wahoo_workouts = $response->json();
        foreach ($wahoo_workouts['workouts'] as $wahoo_workout) {
            History::updateOrCreateFromWahoo($workout, $wahoo_workout);
        }

        return self::SUCCESS;
    }
}
