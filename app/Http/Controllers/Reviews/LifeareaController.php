<?php

namespace App\Http\Controllers\Reviews;

use App\Http\Controllers\Controller;
use App\Models\Reviews\Lifearea;
use App\Models\Reviews\Review;
use Illuminate\Http\Request;

class LifeareaController extends Controller
{
    protected $baseViewPath = 'review.lifearea';

    public function __construct()
    {
        $this->authorizeResource(Lifearea::class, 'lifearea');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Review $review)
    {
        $this->authorize('viewAny', $review);

        if ($request->wantsJson()) {
            return $review->lifeareas()
                ->join('lifeareas', 'lifeareas.id', '=', 'lifearea_review.lifearea_id')
                ->orderBy('lifeareas.title', 'ASC')
                ->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function create(Review $review)
    {
        $this->authorize('create', $review);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reviews\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Review $review)
    {
        $this->authorize('create', $review);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @param  \App\Models\Reviews\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Review $review, Lifearea $lifearea)
    {
        if ($request->wantsJson()) {
            return $lifearea->load([
                'lifearea',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @param  \App\Models\Reviews\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Review $review, Lifearea $lifearea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reviews\Review  $review
     * @param  \App\Models\Reviews\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review, Lifearea $lifearea)
    {
        $attributes = $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'required|integer',
        ]);

        $lifearea->update($attributes);

        if ($request->wantsJson()) {
            return $lifearea->load([
                'lifearea',
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
     * @param  \App\Models\Reviews\Review  $review
     * @param  \App\Models\Reviews\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Review $review, Lifearea $lifearea)
    {
        if ($isDeletable = $lifearea->isDeletable()) {
            $lifearea->delete();
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

        return redirect(route($this->baseViewPath . '.index', ['review' => $review->id]))
            ->with('status', $status);
    }
}
