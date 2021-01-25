<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Services\Service;
use App\Models\Services\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class ServiceController extends Controller
{
    /**
     * Redirect the user to the authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request, string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(string $provider)
    {
        $service = Service::where('slug', $provider)->firstOrFail();

        $provider_user = Socialite::driver($provider)->user();

        $service_user = User::updateOrCreate([
            'user_id' => auth()->user()->id,
            'service_id' => $service->id,
            'service_user_id' => $provider_user->getId(),
        ], [
            'token' => $provider_user->token,
            'token_secret' => null,
            'refresh_token' => $provider_user->refreshToken,
            'expires_in' => $provider_user->expiresIn,
            'expires_at' => ($provider_user->expiresIn ? now()->addSeconds($provider_user->expiresIn) : null),
        ]);

        return redirect(route('user.services.index'))->with('status', [
            'type' => 'success',
            'text' => 'Verbindung mit <b>' . $service->name . '</b> hergestellt.',
        ]);
    }
}
