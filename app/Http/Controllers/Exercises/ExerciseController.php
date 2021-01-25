<?php

namespace App\Http\Controllers\Exercises;

use App\Http\Controllers\Controller;
use App\Models\Exercises\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    protected $baseViewPath = 'exercise';

    public function __construct()
    {
        $this->authorizeResource(Exercise::class, 'exercise');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $exercise);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function edit(Exercise $exercise)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $exercise);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exercise $exercise)
    {
        $attributes = $request->validate([

        ]);

        $exercise->update($attributes);

        if ($request->wantsJson()) {
            return $exercise;
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
     * @param  \App\Models\Workouts\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Exercise $exercise)
    {
        if ($isDeletable = $exercise->isDeletable()) {
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
