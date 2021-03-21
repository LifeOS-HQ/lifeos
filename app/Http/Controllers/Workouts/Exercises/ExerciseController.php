<?php

namespace App\Http\Controllers\Workouts\Exercises;

use App\Http\Controllers\Controller;
use App\Models\Workouts\Exercises\Exercise;
use App\Models\Workouts\Workout;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    protected $baseViewPath = 'workout';

    public function __construct()
    {
        $this->authorizeResource(Workout::class, 'workout');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
    public function store(Request $request, Workout $workout)
    {
        $attributes = $request->validate([
            'exercise_id' => 'required',
        ]);

        $workout_exercise = $workout->exercises()->create([
            'exercise_id' => $attributes['exercise_id'],
            'user_id' => $workout->user_id,
            'goal_type' => 'reps_count',
            'goal_target' => 1,
            'order' => ($workout->exercises()->count() + 1),
        ]);

        // $workout_exercise->sets()->create([
        //     'user_id' => $workout_exercise->user_id,
        //     'exercise_id' => $workout_exercise->exercise_id,
        //     'workout_id' => $workout->id,
        //     'order' => 1,
        //     'reps_count' => 1,
        //     'weight_in_g' => 0,
        // ]);

        return $workout_exercise->load([
            'exercise',
            'sets',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workouts\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function show(Workout $workout, Exercise $exercise)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $exercise);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workouts\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function edit(Workout $workout, Exercise $exercise)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $exercise);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workouts\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workout $workout, Exercise $exercise)
    {
        $attributes = $request->validate([

        ]);

        $exercise->update($attributes);

        if ($request->wantsJson()) {
            return $workout;
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
     * @param  \App\Models\Workouts\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Workout $workout, Exercise $exercise)
    {
        if ($isDeletable = $workout->isDeletable()) {
            $exercise->delete();
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
