<?php

namespace App\Http\Controllers\Behaviours;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Behaviours\Behaviour;
use App\Models\Services\Data\Attributes\Groups\Group;

class BehaviourController extends Controller
{
    protected $baseViewPath = 'behaviour';

    public function __construct()
    {
        $this->authorizeResource(Behaviour::class, 'behaviour');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Behaviour::query()
                ->paginate();
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
            'name' => 'required|string',
        ]);

        $user = auth()->user();

        $behaviour = Behaviour::create($attributes + [
            'user_id' => $user->id,
        ]);

        if ($request->wantsJson()) {
            return $behaviour;
        }

        return redirect($behaviour->path)
            ->with('status', [
                'type' => 'success',
                'text' => $behaviour->label(1) . ' gespeichert.',
            ]);
    }

    public function show(Behaviour $behaviour)
    {
        $behaviour->load([
            'attributes.attribute',
        ]);

        $attribute_groups = Group::query()
            ->with([
                'attributes',
            ])
            ->withoutCustom()
            ->orderBy('name')
            ->get();

        return view($this->baseViewPath . '.show')
            ->with('attribute_groups', $attribute_groups)
            ->with('model', $behaviour);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Behaviours\Behaviour  $behaviour
     * @return \Illuminate\Http\Response
     */
    public function edit(Behaviour $behaviour)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $behaviour);
    }

    public function update(Request $request, Behaviour $behaviour)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
        ]);

        $behaviour->update($attributes);

        if ($request->wantsJson()) {
            return $behaviour;
        }

        return redirect($behaviour->path)
            ->with('status', [
                'type' => 'success',
                'text' => $behaviour->label(1) . ' gespeichert.',
            ]);
    }

    public function destroy(Request $request, Behaviour $behaviour)
    {
        if ($isDeletable = $behaviour->isDeletable()) {
            $behaviour->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $behaviour->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $behaviour->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($behaviour->index_path)
            ->with('status', $status);
    }
}
