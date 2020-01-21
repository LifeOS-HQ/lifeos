<?php

namespace App\Http\Controllers\Journals\Gratitude;

use App\Http\Controllers\Controller;
use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use Illuminate\Http\Request;

class SortController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function index(Journal $journal)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function create(Journal $journal)
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Journals\Journal  $journal
     * @param  \App\Models\Journals\Gratitude\Gratitude  $gratitude
     * @return \Illuminate\Http\Response
     */
    public function show(Journal $journal, Gratitude $gratitude)
    {
        //
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
    public function update(Request $request, Journal $journal)
    {
        $this->authorize('update', $journal);

        $attributes = $request->validate([
            'ranks' => 'required|array',
        ]);

        $journal->load([
            'gratitudes'
        ]);

        foreach ($attributes['ranks'] as $key => $id) {
            if (! $journal->gratitudes->contains('id', $id)) {
                continue;
            }
            Gratitude::where('id', $id)->update([
                'order_column' => ($key + 1),
            ]);
        }

        if ($request->wantsJson()) {
            return $journal;
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
    public function destroy(Journal $journal, Gratitude $gratitude)
    {
        //
    }
}
