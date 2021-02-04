<?php

namespace App\Http\Controllers\Workouts\Exercises;

use App\Http\Controllers\Controller;
use App\Models\Workouts\Exercises\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $baseViewPath = 'history';

    public function __construct()
    {
        $this->authorizeResource(History::class, 'history');
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
     * @param  \App\Models\Workouts\Exercises\History  $history
     * @return \Illuminate\Http\Response
     */
    public function show(History $history)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $history);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workouts\Exercises\History  $history
     * @return \Illuminate\Http\Response
     */
    public function edit(History $history)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $history);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workouts\Exercises\History  $history
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, History $history)
    {
        $attributes = $request->validate([

        ]);

        $history->update($attributes);

        if ($request->wantsJson()) {
            return $history;
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
    public function destroy(Request $request, History $history)
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

        return redirect(route($this->baseViewPath . '.index'))
            ->with('status', $status);
    }
}
