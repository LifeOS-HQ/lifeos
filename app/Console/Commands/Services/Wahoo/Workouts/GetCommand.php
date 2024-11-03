<?php

namespace App\Console\Commands\Services\Wahoo\Workouts;

use App\Apis\Wahoo\Wahoo;
use Illuminate\Console\Command;
use App\Models\Services\Service;

class GetCommand extends Command
{
    protected $signature = 'services:wahoo:workouts:get
        {user : The ID of the user}';

    protected $description = 'Get all Workouts';

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

        $data = $response->json();

        $this->line(json_encode($data, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
