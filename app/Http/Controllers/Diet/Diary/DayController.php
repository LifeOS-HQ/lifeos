<?php

namespace App\Http\Controllers\Diet\Diary;

use App\Http\Controllers\Controller;
use App\Models\Diet\Diary\Day;
use Illuminate\Http\Request;

class DayController extends Controller
{
    protected $baseViewPath = 'diet.diary.day';

    public function __construct()
    {
        $this->authorizeResource(Day::class, 'day');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return auth()->user()->diet_days()->orderBy('at', 'DESC')->paginate();
        }

        return view($this->baseViewPath . '.index');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'at_formatted' => 'required|date_format:d.m.Y',
        ]);

        $day = auth()->user()->diet_days()->create($attributes);

        $day->populateMealsFromLastWeekday();

        $day->refresh();

        return $day;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet\Diary\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function show(Day $day)
    {
        $user = auth()->user();

        $day->load([
            'meals' => function($query) {
                $query->orderBy('at', 'ASC');
            },
        ]);

        return view($this->baseViewPath . '.show')
            ->with('model', $day)
            ->with('foods', \App\Models\Diet\Foods\Food::orderBy('name', 'ASC')->get())
            ->with('diet_meals', $user->diet_meals()->orderBy('name', 'ASC')->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Diary\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function edit(Day $day)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $day);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Diary\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Day $day)
    {
        $attributes = $request->validate([
            'rating_comment' => 'nullable|string',
        ]);

        $day->update($attributes);

        if ($request->wantsJson()) {
            return $day;
        }

        return redirect($day->path)
            ->with('status', [
                'type' => 'success',
                'text' => $day->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diet\Diary\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Day $day)
    {
        if ($isDeletable = $day->isDeletable()) {
            $day->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $day->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $day->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($day->index_path)
            ->with('status', $status);
    }
}
