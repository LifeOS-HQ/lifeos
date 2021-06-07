<?php

namespace App\Http\Controllers\Diet\Diary\Meals;

use App\Http\Controllers\Controller;
use App\Models\Diet\Diary\Day;
use App\Models\Diet\Diary\Meals\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    protected $baseViewPath = 'diet.diary.meal';

    public function __construct()
    {
        $this->authorizeResource(Meal::class, 'meal');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            //
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
    public function store(Request $request, Day $day)
    {
        $day->loadCount('meals');

        return $day->meals()->create([
            'user_id' => $day->user_id,
            'order_by' => $day->meals_count,
            'name' => ($day->meals_count + 1) . '. Mahlzeit',
            'at' => null,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function show(Day $day, Meal $meal)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $meal);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function edit(Day $day, Meal $meal)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $meal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Diary\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Day $day, Meal $meal)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
        ]);

        $meal->update($attributes);

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
