<?php

namespace App\Http\Controllers\Services;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Services\User;
use App\Models\Services\Service;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
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

    public function create(Service $service)
    {
        return view('service.user.create')
            ->with('service', $service);
    }

    public function store(Request $request, Service $service)
    {
        $attributes = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        if ($service->slug === 'habitica') {
            $response = \App\Apis\Habitica\Habitica::login($attributes['username'], $attributes['password']);

            if ($response->failed()) {
                return back()
                    ->with('status', [
                        'type' => 'danger',
                        'text' => 'Verbindung mit <b>' . $service->name . '</b> fehlgeschlagen.',
                ]);
            }

            $response_data = $response->json();

            Arr::forget($attributes, 'password');

            $attributes['username'] = $response_data['data']['username'];
            $attributes['service_user_id'] = $response_data['data']['id'];
            $attributes['token'] = $response_data['data']['apiToken'];
        }
        else {
            $attributes['service_user_id'] = $user->id;
        }

        $service_user = User::updateOrCreate([
            'user_id' => auth()->user()->id,
            'service_id' => $service->id,
            'service_user_id' => $attributes['service_user_id'],
        ], $attributes);

        return redirect(route('user.services.index'))->with('status', [
            'type' => 'success',
            'text' => 'Verbindung mit <b>' . $service->name . '</b> hergestellt.',
        ]);
    }

    public function destroy(User $service_user)
    {
        $service_user->delete();

        return back()->with('status', [
            'type' => 'success',
            'text' => 'Verbindung <b>' . $service_user->service->name . '</b> gelöscht.',
        ]);
    }
}
