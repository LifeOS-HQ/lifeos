<?php

namespace App\Http\Controllers\Workouts;

use App\Http\Controllers\Controller;
use App\Models\Workouts\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller
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
            return auth()->user()
                ->workouts()
                ->search($request->input('searchtext'))
                ->orderBy('name')
                ->get();
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
        $attributes = $request->validate([
            'name' => 'required|string',
        ]);

        return auth()->user()->workouts()->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workouts\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function show(Workout $workout)
    {
        $workout->load([
            'exercises.exercise',
            'exercises.sets',
        ]);

        return view($this->baseViewPath . '.show')
            ->with('model', $workout)
            ->with('exercises', auth()->user()->exercises()->orderBy('name')->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workouts\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function edit(Workout $workout)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $workout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workouts\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workout $workout)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
        ]);

        $workout->update($attributes);

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
    public function destroy(Request $request, Workout $workout)
    {
        if ($isDeletable = $workout->isDeletable()) {
            $workout->delete();
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
