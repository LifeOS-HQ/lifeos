<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Models\Journals\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    protected $baseViewPath = 'journal';

    public function __construct()
    {
        $this->authorizeResource(Journal::class, 'journal');
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
                ->journals()
                ->with([
                    'activities.activity'
                ])
                ->orderBy('date', 'DESC')
                ->paginate();
        }

        return view($this->baseViewPath . '.' . __FUNCTION__)
            ->with('activities', auth()->user()->activities);
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
        return Journal::create([
            'user_id' => auth()->user()->id,
            'date' => today(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $journal
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Journal $journal)
    {
        if ($request->wantsJson()) {
            return $journal;
        }

        return view($this->baseViewPath . '.' . __FUNCTION__)
            ->with('model', $journal);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $journal
     * @return \Illuminate\Http\Response
     */
    public function edit(Journal $journal)
    {
        return view($this->baseViewPath . '.' . __FUNCTION__)
            ->with('model', $journal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $journal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Journal $journal)
    {
        $attributes = $request->validate([
            'date' => 'required|date',
            'body' => 'nullable|string',
            'happiest_moment' => 'nullable|string',
            'rating' => 'nullable|integer|between:1,10',
            'rating_comment' => 'nullable|string',
        ]);

        $journal->update($attributes);

        if ($request->wantsJson()) {
            return $journal;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Tagebucheintrag gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $journal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Journal $journal)
    {
        if ($isDeletable = $journal->isDeletable()) {
            $journal->delete();
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

        return redirect(route($this->baseViewPath . '.index'))
            ->with('status', $status);
    }
}
