<?php

namespace App\Http\Controllers\Services\Data;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Value;
use Illuminate\Http\Request;

class ValueController extends Controller
{
    protected $baseViewPath = 'value';

    public function __construct()
    {
        $this->authorizeResource(Value::class, 'value');
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
     * @param  \App\Models\Services\Data\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function show(Value $value)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $value);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Services\Data\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function edit(Value $value)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $value);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Services\Data\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Value $value)
    {
        $attributes = $request->validate([

        ]);

        $value->update($attributes);

        if ($request->wantsJson()) {
            return $value;
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
     * @param  \App\Models\Services\Data\Value  $value
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Value $value)
    {
        if ($isDeletable = $value->isDeletable()) {
            $value->delete();
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
