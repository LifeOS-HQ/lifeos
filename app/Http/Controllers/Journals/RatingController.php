<?php

namespace App\Http\Controllers\Journals;

use App\Http\Controllers\Controller;
use App\Models\Journals\Journal;
use App\Models\Journals\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected $baseViewPath = 'journal.rating';

    public function __construct()
    {
        $this->authorizeResource(Rating::class, 'rating');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Journal $journal)
    {
        $this->authorize('view', $journal);

        if ($request->wantsJson()) {
            return $journal->ratings()
                ->orderBy('order_column', 'ASC')
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
        $this->authorize('create', $journal);
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
            'title' => 'required|string',
        ]);

        return $journal->ratings()->create([
            'user_id' => $journal->user_id,
        ] + $attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Journal $journal, Rating $rating)
    {
        if ($request->wantsJson()) {
            return $rating;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function edit(Journal $journal, Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Journal $journal, Rating $rating)
    {
        $attributes = $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'nullable|integer',
            'title' => 'required|string',
        ]);

        $rating->update($attributes);

        if ($request->wantsJson()) {
            return $rating;
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
     * @param  \App\Models\Journals\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Journal $journal, Rating $rating)
    {
        if ($isDeletable = $rating->isDeletable()) {
            $rating->delete();
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
