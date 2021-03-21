<?php

namespace App\Http\Controllers\Workouts;

use App\Http\Controllers\Controller;
use App\Models\Workouts\History;
use App\Models\Workouts\Workout;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request, Workout $workout)
    {
        if ($request->wantsJson()) {
            return $workout->histories()
                ->orderBy('start_at', 'DESC')
                ->paginate();
        }

        $workout->load([
            // 'histories',
        ]);

        return view('workout.history.index')
            ->with('model', $workout);
    }

    public function store(Request $request, Workout $workout)
    {
        $workout_history = $workout->histories()->create([
            'user_id' => auth()->user()->id,
            'start_at' => now(),
        ]);

        foreach ($workout->exercises as $key => $exercise) {
            $exercise_history = $workout_history->exercise_histories()->create([
                'user_id' => $workout_history->user_id,
                'exercise_id' => $exercise->exercise_id,
                'goal_type' => $exercise->goal_type,
                'goal_target' => $exercise->goal_target,
            ]);

            foreach ($exercise->sets as $key => $set) {
                $exercise_history->sets()->create([
                    'user_id' => $workout_history->user_id,
                    'workout_history_id' => $workout_history->id,
                    'weight_in_g' => $set->weight_in_g,
                    'reps_count' => $set->reps_count,
                ]);
            }
        }

        if ($request->wantsJson()) {
            return $workout_history;
        }

        return redirect($workout_history->path);
    }

    public function show(Workout $workout, History $history)
    {
        $history->load([
            'exercise_histories.exercise',
            'exercise_histories.sets',
        ]);

        return view('workout.history.show')
            ->with('model', $history)
            ->with('workout', $workout)
            ->with('exercises', auth()->user()->exercises()->orderBy('name')->get());
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

    public function destroy(Request $request, Workout $workout, History $history)
    {
        if ($isDeletable = $history->isDeletable()) {
            $history->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => 'gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => 'kann nicht gelöscht werden.',
            ];
        }

        return redirect($history->index_path)
            ->with('status', $status);
    }
}
