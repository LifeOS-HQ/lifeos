<?php

namespace App\Http\Controllers\Services\Data\Attributes\Groups;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Groups\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $baseViewPath = 'group';

    public function __construct()
    {
        $this->authorizeResource(Group::class, 'group');
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
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $attributes = $request->validate([

        ]);

        $group->update($attributes);

        if ($request->wantsJson()) {
            return $group;
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
     * @param  \App\Models\Services\Data\Attributes\Groups\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group)
    {
        if ($isDeletable = $group->isDeletable()) {
            $group->delete();
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
