<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Services\Service;
use App\Models\Services\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $user->load([
            'services',
        ]);

        return view('service.user.index')
            ->with('services', Service::all())
            ->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Service $service)
    {
        return view('service.user.create')
            ->with('service', $service);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Service $service)
    {
        $attributes = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        $service_user = User::updateOrCreate([
            'user_id' => auth()->user()->id,
            'service_id' => $service->id,
            'service_user_id' => $user->id,
        ], $attributes);

        return redirect(route('user.services.index'))->with('status', [
            'type' => 'success',
            'text' => 'Verbindung mit <b>' . $service->name . '</b> hergestellt.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $service_user)
    {
        $service_user->delete();

        return back()->with('status', [
            'type' => 'success',
            'text' => 'Verbindung <b>' . $service_user->service->name . '</b> gelöscht.',
        ]);
    }
}
