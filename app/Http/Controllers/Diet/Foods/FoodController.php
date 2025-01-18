<?php

namespace App\Http\Controllers\Diet\Foods;

use App\Http\Controllers\Controller;
use App\Models\Diet\Foods\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    protected $baseViewPath = 'diet.food';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Food::query()
                ->search($request->input('searchtext'))
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
        $attributes = $request->validate([

        ]);

        return Food::create([
            'name' => 'Neues Nahrungsmittel',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $food);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Food $food)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $food);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'calories_formatted' => 'required|formatted_number',
            'carbohydrate_formatted' => 'required|formatted_number',
            'fat_formatted' => 'required|formatted_number',
            'protein_formatted' => 'required|formatted_number',
        ]);

        $food->update($attributes);

        if ($request->wantsJson()) {
            return $food;
        }

        return redirect($food->path)
            ->with('status', [
                'type' => 'success',
                'text' => 'gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diet\Foods\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Food $food)
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
                'text' => 'kann nicht gelöscht werden.',
            ];
        }

        return redirect($food->index_path)
            ->with('status', $status);
    }
}
