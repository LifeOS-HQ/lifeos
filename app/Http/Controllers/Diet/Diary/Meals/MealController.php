<?php

namespace App\Http\Controllers\Diet\Diary\Meals;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Diet\Diary\Day;
use App\Http\Controllers\Controller;
use App\Models\Diet\Diary\Meals\Meal;

class MealController extends Controller
{
    protected $baseViewPath = 'diet.diary.meal';

    public function __construct()
    {
        $this->authorizeResource(Meal::class, 'meal');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            //
        }

        return view($this->baseViewPath . '.index');
    }

    public function store(Request $request, Day $day)
    {
        $attributes = $request->validate([
            'meal_id' => 'nullable|integer|exists:diet_days_meals,id',
        ]);

        $day->loadCount('meals');
        $name = ($day->meals_count + 1) . '. Mahlzeit';
        $meal_foods = collect([]);

        if (Arr::has($attributes, 'meal_id')) {
            $old_meal = Meal::query()
                ->with('foods')
                ->find($attributes['meal_id']);

            $name = $old_meal->name;
            $meal_foods = $old_meal->foods;
        }

        $meal = $day->meals()->create([
            'user_id' => $day->user_id,
            'order_by' => $day->meals_count,
            'name' => $name,
            'at' => null,
            'rating_comments' => null,
        ]);

        foreach ($meal_foods as $old_meal_food) {
            $meal->foods()->create([
                'amount' => $old_meal_food->amount,
                'food_id' => $old_meal_food->food_id,
                'meal_id' => $meal->id,
                'user_id' => $meal->user_id,
            ]);
        }

        $meal->setRelation('day', $day);

        return $meal;
    }

    public function show(Day $day, Meal $meal)
    {
        $meal->load([
            'foods',
        ]);

        return view($this->baseViewPath . '.show')
            ->with('model', $meal);
    }

    public function edit(Day $day, Meal $meal)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $meal);
    }

    public function update(Request $request, Day $day, Meal $meal)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'time_formatted' => 'nullable|date_format:H:i',
            'rating_comment' => 'nullable|string',
        ]);

        $meal->update($attributes);

        $meal->load([
            'foods',
        ]);

        if ($request->wantsJson()) {
            return $meal;
        }

        return redirect($meal->path)
            ->with('status', [
                'type' => 'success',
                'text' => $meal->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Day $day, Meal $meal)
    {
        if ($isDeletable = $meal->isDeletable()) {
            $meal->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $meal->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $meal->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($day->path)
            ->with('status', $status);
    }
}
