<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $baseViewPath = 'user.client';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return auth()->user()->clients()->orderBy('name', 'ASC')->paginate();
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
     * @param  \App\Models\Diet\Foods\Food  $client
     * @return \Illuminate\Http\Response
     */
    public function show(User $client)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $client);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Foods\Food  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(User $client)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Foods\Food  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $client)
    {
        $attributes = $request->validate([

        ]);

        $client->update($attributes);

        if ($request->wantsJson()) {
            return $client;
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
     * @param  \App\Models\Diet\Foods\Food  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $client)
    {
        if ($isDeletable = $client->isDeletable()) {
            $client->delete();
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
