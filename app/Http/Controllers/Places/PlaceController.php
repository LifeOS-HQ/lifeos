<?php

namespace App\Http\Controllers\Places;

use App\Http\Controllers\Controller;
use App\Models\Places\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    protected $baseViewPath = 'place';

    public function __construct()
    {
        $this->authorizeResource(Place::class, 'place');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->wantsJson()) {
            return $user->places()
                ->search($request->input('searchtext'))
                ->orderBy('title', 'ASC')
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
            'title' => 'required|string',
        ]);

        return auth()->user()->places()->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Places\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $place);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Places\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $place);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Places\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        $attributes = $request->validate([
            'title' => 'required|string',
        ]);

        $place->update($attributes);

        if ($request->wantsJson()) {
            return $place;
        }

        return redirect($place->path)
            ->with('status', [
                'type' => 'success',
                'text' => $place->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Places\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Place $place)
    {
        if ($isDeletable = $place->isDeletable()) {
            $place->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $place->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $place->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($place->index_path)
            ->with('status', $status);
    }
}
