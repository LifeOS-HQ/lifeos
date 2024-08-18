<?php

namespace App\Http\Controllers\Diet\Diary\Meals;

use App\Http\Controllers\Controller;
use App\Models\Diet\Diary\Meals\Meal;
use App\Models\Diet\Diary\Meals\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    protected $baseViewPath = 'diet.diary.meal.food';

    public function index(Request $request, Meal $meal)
    {
        if ($request->wantsJson()) {
            return $meal->foods()->with([
                'food',
            ])->orderBy('order_by', 'ASC')->get();
        }

        return view($this->baseViewPath . '.index');
    }

    public function store(Request $request, Meal $meal)
    {
        $attributes = $request->validate([
            'food_id' => 'required|exists:food,id',
        ]);

        $user = auth()->user();

        $meal->loadCount('foods');

        $last_food = Food::where('user_id', $user->id)
            ->where('food_id', $attributes['food_id'])
            ->latest()
            ->first();

        return $meal->foods()->create([
            'user_id' => $meal->user_id,
            'food_id' => $attributes['food_id'],
            'order_by' => $meal->foods_count,
            'amount' => $last_food ? $last_food->amount : 1,
        ])->load([
            'food',
        ]);
    }

    public function show(Meal $meal, Food $food)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $food);
    }

    public function edit(Meal $meal, Food $food)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $food);
    }

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
