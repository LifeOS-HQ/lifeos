<?php

namespace App\Http\Controllers\Lifeareas;

use App\Http\Controllers\Controller;
use App\Models\Lifeareas\Lifearea;
use App\Models\Lifeareas\Scale;
use Illuminate\Http\Request;

class ScaleController extends Controller
{
    protected $baseViewPath = 'lifearea.scale';

    public function __construct()
    {
        $this->authorizeResource(Scale::class, 'scale');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Lifearea $lifearea)
    {
        $this->authorize('view', $lifearea);

        if ($request->wantsJson()) {
            return $lifearea->scales()
                ->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function create(Lifearea $lifearea)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Lifearea $lifearea)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return \Illuminate\Http\Response
     */
    public function show(Lifearea $lifearea, Scale $scale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return \Illuminate\Http\Response
     */
    public function edit(Lifearea $lifearea, Scale $scale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lifearea $lifearea, Scale $scale)
    {
        $attributes = $request->validate([
            'description' => 'nullable|string',
        ]);

        $scale->update($attributes);

        if ($request->wantsJson()) {
            return $scale;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'Gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @param  \App\Models\Lifeareas\Scale  $scale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lifearea $lifearea, Scale $scale)
    {
        //
    }
}
