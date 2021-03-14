<?php

namespace App\Http\Controllers\Reviews;

use App\Http\Controllers\Controller;
use App\Models\Reviews\Review;
use App\Models\Services\Data\Attributes\Attribute;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected $baseViewPath = 'review';

    public function __construct()
    {
        $this->authorizeResource(Review::class, 'review');
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
            $reviews = $user->reviews()
                ->with('lifeareas')
                ->latest()
                ->paginate();

            foreach ($reviews as $key => $review) {
                $review->append('lifearea_ratings_avg_formatted');
            }

            return $reviews;
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
        $today = today();

        return Review::create([
            'user_id' => auth()->user()->id,
            'title' => 'Review vom ' . $today->format('d.m.Y'),
            'at' => $today,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        $periods = new CarbonPeriod($review->at->subDays(7), '1 days', $review->at->subDays(1));

        $mood_attribute = Attribute::with([
                'values' => function ($query) use ($review) {
                    return $query->whereDate('at', '<', $review->at);
                },
            ])->where('slug', 'mood')
            ->first();
        $mood_note_attribute = Attribute::with([
                'values' => function ($query) use ($review) {
                    return $query->whereDate('at', '<', $review->at);
                },
            ])->where('slug', 'mood_note')
            ->first();

        $days = [];
        foreach ($periods as $key => $period) {
            $days[$period->format('Y-m-d')] = [
                'date_formatted' => $period->format('d.m.Y'),
                'day_name' => $period->dayName,
                'mood' => $mood_attribute->values->where('at', $period)->first() ?? 0,
                'mood_note' => $mood_note_attribute->values->where('at', $period)->first() ?? '',
            ];
        }

        krsort($days);

        return view($this->baseViewPath . '.show')
            ->with('model', $review->load([
                'blocks',
                'lifeareas.lifearea.scales',
            ]))
            ->with('days', $days);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reviews\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reviews\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        $attributes = $request->validate([
            'at_formatted' => 'required|date_format:d.m.Y',
            'title' => 'required|string',
        ]);

        $review->update($attributes);

        if ($request->wantsJson()) {
            return $review;
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
     * @param  \App\Models\Reviews\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Review $review)
    {
        if ($isDeletable = $review->isDeletable()) {
            $review->delete();
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
