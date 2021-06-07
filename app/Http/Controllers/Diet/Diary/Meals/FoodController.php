<?php

namespace App\Http\Controllers\Diet\Diary\Meals;

use App\Http\Controllers\Controller;
use App\Models\Diet\Diary\Day;
use App\Models\Diet\Diary\Meals\Meal;
use App\Models\Diet\Diary\Meals\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    protected $baseViewPath = 'diet.diary.meal.food';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Meal $meal)
    {
        if ($request->wantsJson()) {
            return $meal->foods()->with([
                'food',
            ])->orderBy('order_by', 'ASC')->get();
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
    public function store(Request $request, Meal $meal)
    {
        $attributes = $request->validate([
            'food_id' => 'required|exists:food,id',
        ]);

        $meal->loadCount('foods');

        return $meal->foods()->create([
            'user_id' => $meal->user_id,
            'food_id' => $attributes['food_id'],
            'order_by' => $meal->foods_count,
            'amount' => 1,
        ])->load([
            'food',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function show(Meal $meal, Food $food)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $food);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function edit(Meal $meal, Food $food)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $food);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meal $meal, Food $food)
    {
        $attributes = $request->validate([
            'amount_formatted' => 'required|formatted_number',
        ]);

        $food->update($attributes);

        if ($request->wantsJson()) {
            return $food->load([
                'food',
            ]);
        }

        return redirect($food->path)
            ->with('status', [
                'type' => 'success',
                'text' => $food->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Meal $meal, Food $food)
    {
        if ($isDeletable = $food->isDeletable()) {
            $food->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $food->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $food->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($meal->path)
            ->with('status', $status);
    }
}
