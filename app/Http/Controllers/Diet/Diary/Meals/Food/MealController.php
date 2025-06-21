<?php

namespace App\Http\Controllers\Diet\Diary\Meals\Food;

use App\Http\Controllers\Controller;
use App\Models\Diet\Diary\Meals\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function store(Request $request, Meal $meal)
    {
        $attributes = $request->validate([
            'meal_id' => 'required|exists:diet_meals,id',
            'clear' => 'required|boolean',
        ]);

        if ($attributes['clear']) {
            $meal->foods()->delete();
        }

        $meal->loadCount('foods');
        $foods_count = $meal->foods_count;

        $added_meal = \App\Models\Diet\Meals\Meal::with('foods.food')->find($attributes['meal_id']);

        $added_foods = [];
        foreach ($added_meal->foods as $added_meal_food) {
            $added_foods[] = $meal->foods()->create([
                'user_id' => $meal->user_id,
                'food_id' => $added_meal_food->food->id,
                'order_by' => $foods_count,
                'amount' => $added_meal_food->amount,
            ])->load([
                'food',
            ]);
            $foods_count++;
        }

        $meal->update([
            'name' => $added_meal->name,
        ]);

        return [
            'meal' => $meal,
            'foods' => $added_foods,
        ];
    }
}
