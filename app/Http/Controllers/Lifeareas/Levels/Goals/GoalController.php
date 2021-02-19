<?php

namespace App\Http\Controllers\Lifeareas\Levels\Goals;

use App\Http\Controllers\Controller;
use App\Models\Lifeareas\Levels\Goals\Goal;
use App\Models\Lifeareas\Lifearea;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    protected $baseViewPath = 'lifearea.level.goal';

    public function __construct()
    {
        // $this->authorizeResource(Goal::class, 'goal');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Lifearea $lifearea, int $level)
    {
        //
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
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function show(Goal $goal)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $goal);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function edit(Goal $goal)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $goal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goal $goal)
    {
        $attributes = $request->validate([

        ]);

        $goal->update($attributes);

        if ($request->wantsJson()) {
            return $goal;
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
     * @param  \App\Models\Lifeareas\Levels\Goals\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Goal $goal)
    {
        if ($isDeletable = $goal->isDeletable()) {
            $goal->delete();
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
