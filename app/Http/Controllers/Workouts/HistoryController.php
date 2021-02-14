<?php

namespace App\Http\Controllers\Workouts;

use App\Http\Controllers\Controller;
use App\Models\Workouts\History;
use App\Models\Workouts\Workout;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function store(Request $request, Workout $workout)
    {
        $workout_history = $workout->histories()->create([
            'user_id' => auth()->user()->id,
            'start_at' => now(),
        ]);

        foreach ($workout->exercises as $key => $exercise) {
            $exercise_history = $workout_history->exercise_histories()->create([
                'user_id' => $workout_history->user_id,
                'exercise_id' => $exercise->id,
                'goal_type' => $exercise->pivot->goal_type,
                'goal_target' => $exercise->pivot->goal_target,
            ]);

            $exercise_history->sets()->create([
                'user_id' => $workout_history->user_id,
                'workout_history_id' => $workout_history->id,
            ]);
        }


        return redirect($workout_history->path);
    }

    public function show(Workout $workout, History $history)
    {
        $workout->load([
            'exercises',
        ]);

        return view('workout.history.show')
            ->with('model', $history)
            ->with('workout', $workout);
    }

    public function update(Request $request, Workout $workout, History $history)
    {
        $history->update([
            'end_at' => now(),
        ]);

        return redirect($workout->path)
            ->with('status', [
                'type' => 'success',
                'text' => 'Training beendet.',
            ]);
    }
}
