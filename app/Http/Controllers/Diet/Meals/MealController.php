<?php

namespace App\Http\Controllers\Diet\Meals;

use App\Http\Controllers\Controller;
use App\Models\Diet\Meals\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    protected $baseViewPath = 'diet.meal';

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
            return auth()->user()->diet_meals()
                ->orderBy('name', 'ASC')
                ->paginate();
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
        return auth()->user()->diet_meals()->create([
            'name' => 'Neue Mahlzeit',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function show(Meal $meal)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $meal)
            ->with('foods', \App\Models\Diet\Foods\Food::orderBy('name', 'ASC')->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function edit(Meal $meal)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $meal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meal $meal)
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
     * @param  \App\Models\Diet\Meals\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Meal $meal)
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

        return redirect($meal->index_path)
            ->with('status', $status);
    }
}
