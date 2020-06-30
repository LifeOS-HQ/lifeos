<?php

namespace App\Http\Controllers\Activities;

use App\Http\Controllers\Controller;
use App\Models\Activities\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $baseViewPath = 'activity';

    public function __construct()
    {
        $this->authorizeResource(Activity::class, 'activity');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->wantsJson()) {
            return $user->activities()
                ->with([
                    'lifearea',
                ])
                ->search($request->input('searchtext'))
                ->lifearea($request->input('lifearea_id'))
                ->orderBy('title', 'ASC')
                ->paginate();
        }

        return view($this->baseViewPath . '.index')
            ->with('lifeareas', $user->lifeareas);
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
            'title' => 'required|string',
            'lifearea_id' => 'nullable|integer'
        ]);

        return auth()->user()->activities()->create($attributes)->fresh()->load([
            'lifearea',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activities\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $activity);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activities\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $activity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activities\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        $attributes = $request->validate([
            'title' => 'required|string',
            'lifearea_id' => 'nullable|integer'
        ]);

        $activity->update($attributes);

        if ($request->wantsJson()) {
            return $activity->load([
                'lifearea',
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
     * @param  \App\Models\Activities\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Activity $activity)
    {
        if ($isDeletable = $activity->isDeletable()) {
            $activity->delete();
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
