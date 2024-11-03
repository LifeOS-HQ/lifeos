<?php

namespace App\Apis\Wahoo;

use App\Models\Services\User as ServiceUser;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class Wahoo
{
    const BASE_URL = 'https://api.wahooligan.com/v1/';

    private ServiceUser $service_user;

    public function __construct(ServiceUser $service_user)
    {
        $this->service_user = $service_user;
    }

    private function getClient(): PendingRequest
    {
        $this->ensureValidToken();

        return Http::baseUrl(self::BASE_URL)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withToken($this->service_user->token)
            ->acceptJson();
    }

    private function ensureValidToken(): void
    {
        if ($this->service_user->expires_at->isFuture()) {
            return;
        }

        $this->refresh();
    }

    private function refresh(): void
    {
        $response = Http::post('https://api.wahooligan.com/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->service_user->refresh_token,
            'client_id' => config('services.wahoo.client_id'),
            'client_secret' => config('services.wahoo.client_secret'),
        ]);

        $this->service_user->update([
            'token' => $response['access_token'],
            'refresh_token' => $response['refresh_token'],
            'expires_in' => $response['expires_in'],
            'expires_at' => now()->addSeconds($response['expires_in']),
        ]);
    }

    public function getUser(): Response
    {
        return $this->getClient()->get('user');
    }

    public function getWorkouts(): Response
    {
        return $this->getClient()->get('workouts');
    }

}

?>
