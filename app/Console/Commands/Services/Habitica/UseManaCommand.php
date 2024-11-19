<?php

namespace App\Console\Commands\Services\Habitica;

use App\Models\Services\Service;
use Illuminate\Console\Command;

class UseManaCommand extends Command
{
    const MANA_COST_TOOLS_OF_TRADE = 25;
    const MANA_COST_BACK_STAB = 15;

    protected $signature = 'services:habitica:use-mana {user}';

    protected $description = 'Uses all mana of the user';

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

        $response = $api->getUser();

        if ($response->failed()) {
            $this->error('Failed to get user');

            return self::FAILURE;
        }
        $response_data = $response->json();

        $habitica_user_data = $response_data['data'];
        $mana_available = $habitica_user_data['stats']['mp'];

        $this->line('mana available: ' . $mana_available);

        if ($mana_available >= self::MANA_COST_TOOLS_OF_TRADE) {
            $this->line('cast tools of trade');
            $response = $api->cast('toolsOfTrade');

            if ($response->failed()) {
                $this->error('Failed to cast tools of trade');

                return self::FAILURE;
            }

            $response_data = $response->json();

            $mana_available = $response_data['data']['user']['stats']['mp'];
            sleep(1);
        }

        while ($mana_available >= self::MANA_COST_BACK_STAB) {
            $this->line('cast backstab');
            $response = $api->cast('backStab', '4eba0a20-66f5-4b19-afce-b9256d595d18');

            if ($response->failed()) {
                break;
            }

            $response_data = $response->json();

            $mana_available = $response_data['data']['user']['stats']['mp'];
            sleep(1);
        }

        $this->line('mana available: ' . $mana_available);

        return self::SUCCESS;
    }
}
