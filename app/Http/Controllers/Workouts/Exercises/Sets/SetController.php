<?php

namespace App\Http\Controllers\Workouts\Exercises\Sets;

use App\Http\Controllers\Controller;
use App\Models\Workouts\Exercises\Exercise;
use App\Models\Workouts\Set;
use App\Models\Workouts\Workout;
use Illuminate\Http\Request;

class SetController extends Controller
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
    public function index(Request $request, Workout $workout, Exercise $exercise)
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
    public function store(Request $request, Workout $workout, Exercise $exercise)
    {
        $sets = $exercise->sets;

        $attributes = [
            'user_id' => $exercise->user_id,
            'exercise_id' => $exercise->exercise_id,
            'workout_id' => $workout->id,
            'order' => ($sets->count() + 1),
        ];

        if ($sets->count()) {
            $last_set = $sets->last();
            $attributes['reps_count'] = $last_set->reps_count;
            $attributes['weight_in_g'] = $last_set->weight_in_g;
        }
        else {
            $attributes['reps_count'] = 1;
            $attributes['weight_in_g'] = 0;
        }

        $set = $exercise->sets()->create($attributes);

        return $set;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workouts\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Workout $workout, Exercise $exercise, Set $set)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $set);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workouts\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function edit(Set $set)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $set);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workouts\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workout $workout, Exercise $exercise, Set $set)
    {
        $attributes = $request->validate([
            'weight_in_kg_formatted' => 'required|formatted_number',
            'reps_count' => 'required|integer',
        ]);

        $set->update($attributes);

        if ($request->wantsJson()) {
            return $set;
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
     * @param  \App\Models\Workouts\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Workout $workout, Exercise $exercise, Set $set)
    {
        if ($isDeletable = $set->isDeletable()) {
            $set->delete();
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
