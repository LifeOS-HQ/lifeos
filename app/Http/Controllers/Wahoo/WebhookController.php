<?php

namespace App\Http\Controllers\Wahoo;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Services\Service;
use App\Models\Workouts\History;
use App\Models\Workouts\Workout;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class WebhookController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Wahoo Webhook', [
            'headers' => $request->header(),
            'request' => $request->all(),
        ]);

        $event_type = $request->input('event_type');

        return match ($event_type) {
            'workout_summary' => $this->handleWorkoutSummary($request->all()),
            default => null,
        };

        return [
            //
        ];
    }

    private function handleWorkoutSummary(array $data)
    {
        $service = Service::where('slug', 'wahoo')->first();

        $service_user = \App\Models\Services\User::query()
            ->where('service_id', $service->id)
            ->where('service_user_id', $data['user']['id'])
            ->first();

        $workout = Workout::where('user_id', $service_user->user_id)
            ->where('name', 'Zwift 750 kcal')
            ->first();

        $wahoo_workout = $data['workout_summary']['workout'];
        Arr::forget($data, 'workout_summary.workout');
        $wahoo_workout['workout_summary'] = $data['workout_summary'];

        History::updateOrCreateFromWahoo($workout, $wahoo_workout);
    }
}
