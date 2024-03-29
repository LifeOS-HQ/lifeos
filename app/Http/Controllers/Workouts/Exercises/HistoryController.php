<?php

namespace App\Http\Controllers\Workouts\Exercises;

use App\Http\Controllers\Controller;
use App\Models\Workouts\Exercises\History as ExerciseHistory;
use App\Models\Workouts\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $baseViewPath = 'history';

    public function __construct()
    {
        // $this->authorizeResource(History::class, 'history');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, History $history)
    {
        if ($request->wantsJson()) {
            //
        }

        return view($this->baseViewPath . '.index');
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
    public function store(Request $request, History $history)
    {
        $attributes = $request->validate([
            'exercise_id' => 'required',
        ]);

        $exercise_history = $history->exercise_histories()->create([
            'user_id' => $history->user_id,
            'exercise_id' => $attributes['exercise_id'],
            'goal_type' => 'reps',
            'goal_target' => 1,
        ]);

        $exercise_history->sets()->create([
            'user_id' => $history->user_id,
            'workout_history_id' => $history->id,
        ]);

        return $exercise_history->load([
            'exercise',
            'sets',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workouts\Exercises\History  $history
     * @return \Illuminate\Http\Response
     */
    public function show(History $history, ExerciseHistory $exercise_history)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $exercise_history);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workouts\Exercises\History  $history
     * @return \Illuminate\Http\Response
     */
    public function edit(History $history, ExerciseHistory $exercise_history)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $exercise_history);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workouts\Exercises\History  $history
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, History $history, ExerciseHistory $exercise_history)
    {
        $attributes = $request->validate([

        ]);

        $exercise_history->update($attributes);

        if ($request->wantsJson()) {
            return $exercise_history;
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
     * @param  \App\Models\Workouts\Exercises\History  $history
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, History $history, ExerciseHistory $exercise_history)
    {
        if ($isDeletable = $history->isDeletable()) {
            $exercise_history->delete();
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
