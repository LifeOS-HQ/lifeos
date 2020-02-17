<?php

namespace App\Http\Controllers\Reviews;

use App\Http\Controllers\Controller;
use App\Models\Reviews\Block;
use App\Models\Reviews\Review;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    protected $baseViewPath = 'review.block';

    public function __construct()
    {
        $this->authorizeResource(Block::class, 'block');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Review $review)
    {
        $this->authorize('view', $review);

        if ($request->wantsJson()) {
            return $review->blocks()
                ->orderBy('order_column', 'ASC')
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

        return $review->blocks()->create([
            'user_id' => $review->user_id,
            'title' => 'Neuer Block',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @param  \App\Models\Reviews\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Review $review, Block $block)
    {
        if ($request->wantsJson()) {
            return $block;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @param  \App\Models\Reviews\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Review $review, Block $block)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reviews\Review  $review
     * @param  \App\Models\Reviews\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review, Block $block)
    {
        $attributes = $request->validate([
            'title' => 'required|string',
            'body' => 'nullable|string',
        ]);

        $block->update($attributes);

        if ($request->wantsJson()) {
            return $block;
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
     * @param  \App\Models\Reviews\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Review $review, Block $block)
    {
        if ($isDeletable = $block->isDeletable()) {
            $block->delete();
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
