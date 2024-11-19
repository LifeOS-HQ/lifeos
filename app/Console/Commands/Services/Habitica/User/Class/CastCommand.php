<?php

namespace App\Console\Commands\Services\Habitica\User\Class;

use Illuminate\Console\Command;
use App\Models\Services\Service;

class CastCommand extends Command
{
    protected $signature = 'services:habitica:user:class:cast
        {user : user id}
        {spell : spell slug}
        {--target= : target user id}';

    protected $description = 'casts a skill on Habitica';

    public function handle()
    {
        $habitica_service = Service::where('slug', 'habitica')->first();

        $service_user = \App\Models\Services\User::where('user_id', $this->argument('user'))
            ->where('service_id', $habitica_service->id)
            ->first();

        if (is_null($service_user)) {
            $this->error('User not found');

            return self::FAILURE;
        }

        $api = new \App\Apis\Habitica\Habitica($service_user);

        $response = $api->cast($this->argument('spell'), $this->option('target') ?? '');

        $response_data = $response->json();

        $this->line((json_encode($response_data, JSON_PRETTY_PRINT)));

        return self::SUCCESS;
    }
}
