<?php

namespace App\Http\Controllers\Journals\Activities;

use App\Http\Controllers\Controller;
use App\Models\Journals\Activities\Activity;
use App\Models\Journals\Journal;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $baseViewPath = 'journal.activity';

    public function __construct()
    {
        $this->authorizeResource(Activity::class, 'activity');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Journal $journal)
    {
        $this->authorize('viewAny', $journal);

        if ($request->wantsJson()) {
            return $journal->activities()
                ->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function create(Journal $journal)
    {
        $this->authorize('viewAny', $journal);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Journals\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Journal $journal)
    {
        $this->authorize('create', $journal);

        $attributes = $request->validate([
            'activity_id' => 'required|integer|exists:activities,id'
        ]);

        return $journal->activities()
            ->create($attributes)
            ->load([
                'activity'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Activities\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Journal $journal, Activity $activity)
    {
        if ($request->wantsJson()) {
            return $activity;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Activities\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Journal $journal, Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Activities\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Journal $journal, Activity $activity)
    {
        $attributes = $request->validate([
            'activity_id' => 'required|integer|exists:activities,id',
            'comment' => 'nullable|string',
            'rating' => 'nullable|integer',
        ]);

        $activity->update($attributes);

        if ($request->wantsJson()) {
            return $activity->load([
                'activity'
            ]);
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Activities\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Journal $journal, Activity $activity)
    {
        if ($isDeletable = $activity->isDeletable()) {
            $activity->delete();
        }

        if ($request->wantsJson())
        {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => 'Datensatz gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => 'Datensatz kann nicht gelöscht werden.',
            ];
        }

        return redirect(route($this->baseViewPath . '.index', ['journal' => $journal->id]))
            ->with('status', $status);
    }
}
