<?php

namespace App\Console\Commands\Services\Habitica\Tasks;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use App\Models\Services\Service;
use App\Models\Behaviours\Behaviour;

class ImportCommand extends Command
{
    protected $signature = 'services:habitica:tasks:import {user}';

    protected $description = 'Imports tasks and its history from Habitica';

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

        $response = $api->getTasks([
            'type' => 'dailys',
        ]);

        if ($response->failed()) {
            $this->error('Failed to get tasks');

            return self::FAILURE;
        }

        $data = $response->json();
        $tasks = $data['data'];
        foreach ($tasks as $task) {
            $this->info('Processing task: ' . $task['text']);
            $this->import($task);
        }

        return self::SUCCESS;
    }

    private function import(array $task): void
    {
        $behaviour = Behaviour::firstOrCreate([
            'source_slug' => 'habitica',
            'source_id' => $task['id'],
        ], [
            'user_id' => $this->argument('user'),
            'name' => $task['text'],
        ]);

        foreach ($task['history'] as $history) {
            $at = Carbon::createFromTimestamp($history['date'] / 1000, 'UTC');
            $is_completed = Arr::get($history, 'completed', true);
            $this->line('Processing history: ' . $at->format('Y-m-d H:i:s') . ' - ' . ($is_completed ? 'completed' : 'not completed'));
            if ($is_completed === false) {
                continue;
            }
            $behaviour->histories()->updateOrCreate([
                'source_slug' => 'habitica',
                'source_id' => $history['date'],
            ], [
                'end_at' => $at,
                'is_committed' => true,
                'is_completed' => true,
                'user_id' => $behaviour->user_id,
                'start_at' => $at,
            ]);
        }
    }
}
