<?php

namespace App\Http\Controllers\Services\Data;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    protected $baseViewPath = 'type';

    public function __construct()
    {
        $this->authorizeResource(Type::class, 'type');
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
     * @param  \App\Models\Services\Data\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Services\Data\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Services\Data\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        $attributes = $request->validate([

        ]);

        $type->update($attributes);

        if ($request->wantsJson()) {
            return $type;
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
     * @param  \App\Models\Services\Data\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Type $type)
    {
        if ($isDeletable = $type->isDeletable()) {
            $type->delete();
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
