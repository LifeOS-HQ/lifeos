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
        $scale = $lifearea->scales()->where('value', $level)->first();
        if (is_null($scale)) {
            dd('test');
            abort(404);
        }

        return $scale->goals()->with([
            'data_attribute',
        ])->get();
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
    public function store(Request $request, Lifearea $lifearea, int $level)
    {
        $scale = $lifearea->scales()->where('value', $level)->first();
        if (is_null($scale)) {
            dd('test');
            abort(404);
        }

        $attributes = $request->validate([
            'data_attribute_id' => 'required|integer|exists:data_attributes,id',
        ]);

        return $scale->goals()->create($attributes + [
            'end' => 0,
            'lifearea_id' => $lifearea->id,
            'start' => 0,
            'user_id' => auth()->user()->id,
        ])->load([
            'data_attribute',
        ]);
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
    public function update(Request $request, Lifearea $lifearea, int $level, Goal $goal)
    {
        $attributes = $request->validate([
            'data_attribute_id' => 'required|integer|exists:data_attributes,id',
            'end_formatted' => 'required|formatted_number',
            'start_formatted' => 'required|formatted_number',
        ]);

        $goal->update($attributes);

        if ($request->wantsJson()) {
            return $goal->load([
                'data_attribute',
            ]);
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
    public function destroy(Request $request, Lifearea $lifearea, int $level, Goal $goal)
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

        return redirect($goal->index_path)
            ->with('status', $status);
    }
}
