<?php

namespace App\Http\Controllers\Lifeareas;

use App\Http\Controllers\Controller;
use App\Models\Lifeareas\Lifearea;
use Illuminate\Http\Request;

class LifeareaController extends Controller
{
    protected $baseViewPath = 'lifearea';

    public function __construct()
    {
        $this->authorizeResource(Lifearea::class, 'lifearea');
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
            $models = $user->lifeareas()
                ->with([
                    'ratings',
                ])
                ->orderBy('title', 'ASC')
                ->get();

            foreach ($models as $key => $model) {
                $model->append('ratings_avg_formatted');
            }

            return $models;
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

        return auth()->user()->lifeareas()->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function show(Lifearea $lifearea)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $lifearea->load([
                'ratings.review',
                'scales',
            ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function edit(Lifearea $lifearea)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $lifearea);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lifearea $lifearea)
    {
        $attributes = $request->validate([
            'title' => 'required|string',
        ]);

        $lifearea->update($attributes);

        if ($request->wantsJson()) {
            return $lifearea;
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
     * @param  \App\Models\Lifeareas\Lifearea  $lifearea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Lifearea $lifearea)
    {
        if ($isDeletable = $lifearea->isDeletable()) {
            $lifearea->delete();
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
