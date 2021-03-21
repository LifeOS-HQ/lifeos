<?php

namespace App\Http\Controllers\Workouts\Exercises\Sets;

use App\Http\Controllers\Controller;
use App\Models\Workouts\Exercises\History as ExerciseHistory;
use App\Models\Workouts\History;
use App\Models\Workouts\Set;
use App\Models\Workouts\Sets\History as SetHistory;
use App\Models\Workouts\Workout;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $baseViewPath = 'set';

    public function __construct()
    {
        // $this->authorizeResource(Set::class, 'set');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, History $history, ExerciseHistory $exercise_history)
    {
        if ($request->wantsJson()) {
            //
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, History $history, ExerciseHistory $exercise_history)
    {
        $set_historys = $exercise_history->sets;

        $attributes = [
            'user_id' => $exercise_history->user_id,
            'workout_exercise_history_id' => $exercise_history->exercise_id,
            'workout_history_id' => $history->id,
            'order' => ($set_historys->count() + 1),
        ];

        if ($set_historys->count()) {
            $last_set = $set_historys->last();
            $attributes['reps_count'] = $last_set->reps_count;
            $attributes['weight_in_g'] = $last_set->weight_in_g;
        }
        else {
            $attributes['reps_count'] = 1;
            $attributes['weight_in_g'] = 0;
        }

        $set_history = $exercise_history->sets()->create($attributes);

        return $set_history;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workouts\Set  $set_history
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Workout $history, ExerciseHistory $exercise_history, SetHistory $set_history)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $set_history);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workouts\Set  $set_history
     * @return \Illuminate\Http\Response
     */
    public function edit(SetHistory $set_history)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $set_history);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workouts\Set  $set_history
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, History $history, ExerciseHistory $exercise_history, SetHistory $set_history)
    {
        $attributes = $request->validate([
            'weight_in_kg_formatted' => 'required|formatted_number',
            'reps_count' => 'required|integer',
        ]);

        $set_history->update($attributes + [
            'is_completed' => true,
        ]);

        if ($request->wantsJson()) {
            return $set_history;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workouts\Set  $set_history
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, History $history, ExerciseHistory $exercise_history, SetHistory $set_history)
    {
        if ($isDeletable = $set_history->isDeletable()) {
            $set_history->delete();
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

        return redirect(route($this->baseViewPath . '.index'))
            ->with('status', $status);
    }
}
