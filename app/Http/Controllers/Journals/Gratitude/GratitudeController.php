<?php

namespace App\Http\Controllers\Journals\Gratitude;

use App\Http\Controllers\Controller;
use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use Illuminate\Http\Request;

class GratitudeController extends Controller
{
    protected $baseViewPath = 'journal.gratitude';

    public function __construct()
    {
        $this->authorizeResource(Gratitude::class, 'gratitude');
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
            return $journal->gratitudes()
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
            'text' => 'required|string',
        ]);

        return $journal->gratitudes()->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Gratitude\Gratitude  $gratitude
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Journal $journal, Gratitude $gratitude)
    {
        if ($request->wantsJson()) {
            return $gratitude;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Gratitude\Gratitude  $gratitude
     * @return \Illuminate\Http\Response
     */
    public function edit(Journal $journal, Gratitude $gratitude)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Gratitude\Gratitude  $gratitude
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Journal $journal, Gratitude $gratitude)
    {
        $attributes = $request->validate([
            'text' => 'required|string',
        ]);

        $gratitude->update($attributes);

        if ($request->wantsJson()) {
            return $gratitude;
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
     * @param  \App\Models\Journals\Gratitude\Gratitude  $gratitude
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Journal $journal, Gratitude $gratitude)
    {
        if ($isDeletable = $gratitude->isDeletable()) {
            $gratitude->delete();
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
                'text' => 'Tagebucheintrag gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => 'Tagebucheintrag kann nicht gelöscht werden.',
            ];
        }

        return redirect(route($this->baseViewPath . '.index', ['journal' => $journal->id]))
            ->with('status', $status);
    }
}
