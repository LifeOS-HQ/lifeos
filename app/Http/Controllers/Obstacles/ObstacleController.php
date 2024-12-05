<?php

namespace App\Http\Controllers\Obstacles;

use App\Http\Controllers\Controller;
use App\Models\Obstacles\Obstacle;
use Illuminate\Http\Request;

class ObstacleController extends Controller
{
    protected $baseViewPath = 'obstacle';

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Obstacle::query()
                ->where('user_id', auth()->id())
                ->orderBy('title')
                ->latest()
                ->get();
        }

        return view($this->baseViewPath . '.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([

        ]);

        $attributes['user_id'] = auth()->id();

        $obstacle = Obstacle::create($attributes);

        if ($request->wantsJson()) {
            return $obstacle;
        }

        return redirect($obstacle->path)
            ->with('status', [
                'type' => 'success',
                'text' => $obstacle->label(1) . ' gespeichert.',
            ]);
    }

    public function show(Obstacle $obstacle)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $obstacle);
    }

    public function edit(Obstacle $obstacle)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $obstacle);
    }

    public function update(Request $request, Obstacle $obstacle)
    {
        $attributes = $request->validate([
            'level' => 'required|integer',
            'title' => 'present|nullable|string',
            'whish' => 'present|nullable|string',
            'outcome' => 'present|nullable|string',
            'obstacle' => 'present|nullable|string',
            'plan' => 'present|nullable|string',
            'loot' => 'present|nullable|string',
        ]);

        $obstacle->update($attributes);

        if ($request->wantsJson()) {
            return $obstacle;
        }

        return redirect($obstacle->path)
            ->with('status', [
                'type' => 'success',
                'text' => $obstacle->label(1) . ' gespeichert.',
            ]);
    }

    public function destroy(Request $request, Obstacle $obstacle)
    {
        if ($isDeletable = $obstacle->isDeletable()) {
            $obstacle->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $obstacle->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $obstacle->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($obstacle->index_path)
            ->with('status', $status);
    }
}
