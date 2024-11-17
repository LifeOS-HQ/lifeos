<?php

namespace App\Http\Controllers\Behaviours;

use Illuminate\Http\Request;
use App\Models\Services\Service;
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

        $habitica_service = Service::where('slug', 'habitica')->first();
        $service_user = \App\Models\Services\User::where('user_id', $user->id)
            ->where('service_id', $habitica_service->id)
            ->first();

        if ($service_user) {
            $api = new \App\Apis\Habitica\Habitica($service_user);

            $habitica_task = $api->createTask([
                'text' => $behaviour->name,
                'type' => 'daily',
                'frequency' => 'weekly',
                'repeat' => [
                    'su' => false,
                    'm' => false,
                    't' => false,
                    'w' => false,
                    'th' => false,
                    'f' => false,
                    's' => false,
                ],
            ]);

            $behaviour->update([
                'habitica_uuid' => $habitica_task['data']['id'],
            ]);
        }

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
            'dataAttributes.attribute',
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

        $user = auth()->user();

        $behaviour->update($attributes);

        if ($behaviour->habitica_uuid) {
            $habitica_service = Service::where('slug', 'habitica')->first();
            $service_user = \App\Models\Services\User::where('user_id', $user->id)
                ->where('service_id', $habitica_service->id)
                ->first();

            if ($service_user) {
                $api = new \App\Apis\Habitica\Habitica($service_user);

                $habitica_task = $api->updateTask($behaviour->habitica_uuid, [
                    'text' => $behaviour->name,
                ]);
            }
        }

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
