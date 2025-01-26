<?php

namespace App\Http\Controllers\Habitica;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Services\Service;
use App\Models\Behaviours\History;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Behaviours\Behaviour;

class WebhookController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Wahoo Webhook', [
            'headers' => $request->header(),
            'request' => $request->all(),
        ]);

        $event_type = $request->input('type');

        return match ($event_type) {
            'scored' => $this->handleScored($request->all()),
            default => null,
        };
    }

    private function handleScored(array $data)
    {
        $service = Service::where('slug', 'habitica')->first();

        $service_user = \App\Models\Services\User::query()
            ->where('service_id', $service->id)
            ->where('service_user_id', $data['user']['_id'])
            ->first();

        if (is_null($service_user)) {
            return;
        }

        $task = Arr::get($data, 'task');

        $behaviour = Behaviour::firstOrCreate([
            'source_slug' => 'habitica',
            'source_id' => $task['id'],
        ], [
            'user_id' => $service_user->user_id,
            'name' => $task['text'],
        ]);

        foreach ($task['history'] as $history) {
            $at = Carbon::createFromTimestamp($history['date'] / 1000, 'UTC');
            $is_completed = Arr::get($history, 'completed', true);
            if ($is_completed === false) {
                continue;
            }
            $history = History::updateOrCreateFromHabitica($behaviour, [
                'date' => $history['date'],
                'completed' => $is_completed,
            ]);

            $history->cache();
        }

        return;
    }
}
