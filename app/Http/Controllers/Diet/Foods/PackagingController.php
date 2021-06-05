<?php

namespace App\Http\Controllers\Diet\Foods;

use App\Http\Controllers\Controller;
use App\Models\Diet\Foods\Food;
use App\Models\Diet\Foods\Packaging;
use Illuminate\Http\Request;

class PackagingController extends Controller
{
    protected $baseViewPath = 'packaging';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Food $food)
    {
        if ($request->wantsJson()) {
            return $food->packagings()->orderBy('amount', 'ASC')->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Food $food)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Food $food)
    {
        $attributes = $request->validate([
            'amount_formatted' => 'required|formatted_number',
        ]);

        return $food->packagings()->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food, Packaging $packaging)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $packaging);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return \Illuminate\Http\Response
     */
    public function edit(Food $food, Packaging $packaging)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $packaging);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food, Packaging $packaging)
    {
        $attributes = $request->validate([
            'amount_formatted' => 'required|formatted_number',
        ]);

        $packaging->update($attributes);

        if ($request->wantsJson()) {
            return $packaging;
        }

        return redirect($packaging->path)
            ->with('status', [
                'type' => 'success',
                'text' => $packaging->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diet\Foods\Packaging  $packaging
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Food $food, Packaging $packaging)
    {
        if ($isDeletable = $packaging->isDeletable()) {
            $packaging->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $packaging->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $packaging->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($food->path)
            ->with('status', $status);
    }
}
