<?php

namespace App\Http\Controllers\Workouts;

use App\Http\Controllers\Controller;
use App\Models\Workouts\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    protected $baseViewPath = 'set';

    public function __construct()
    {
        $this->authorizeResource(Set::class, 'set');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workouts\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function show(Set $set)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $set);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workouts\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function edit(Set $set)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $set);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workouts\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Set $set)
    {
        $attributes = $request->validate([

        ]);

        $set->update($attributes);

        if ($request->wantsJson()) {
            return $set;
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
     * @param  \App\Models\Workouts\Set  $set
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Set $set)
    {
        if ($isDeletable = $set->isDeletable()) {
            $set->delete();
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
