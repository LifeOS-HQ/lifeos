<?php

namespace App\Http\Controllers\Services\Data\Attributes;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    protected $baseViewPath = 'attribute';

    public function __construct()
    {
        $this->authorizeResource(Attribute::class, 'attribute');
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
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $attribute);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $attribute);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        $attributes = $request->validate([

        ]);

        $attribute->update($attributes);

        if ($request->wantsJson()) {
            return $attribute;
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
     * @param  \App\Models\Services\Data\Attributes\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Attribute $attribute)
    {
        if ($isDeletable = $attribute->isDeletable()) {
            $attribute->delete();
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
