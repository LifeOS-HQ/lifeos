<?php

namespace App\Apis\Exist;

use App\Models\Services\User;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http as BaseHttp;

class Http extends Factory
{
    protected static $access_token;

    public const PROVIDED_ATTRIBUTES = [
        'meditation_min',
        'energy',
        'carbohydrates',
        'fat',
        'protein',
    ];

    public static function __callStatic($method, $parameters)
    {
        return self::builtClient()->$method(...$parameters);
    }

    public static function setAccessToken(string $access_token)
    {
        self::$access_token = $access_token;
    }

    protected static function builtClient()
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if (self::$access_token) {
            $headers['Authorization'] = 'Bearer ' . self::$access_token;
        }

        return BaseHttp::withOptions([
            'headers' => $headers,
        ])
            ->acceptJson()
            ->baseUrl('https://exist.io/api/2/')
            ->withOptions([
                'debug' => false,
            ]);
    }

    public static function get(string $url, array $query = [])
    {
        $response = self::builtClient()->get($url, $query);

        return self::handleResponse($response);
    }

    public static function post(string $url, array $data = [])
    {
        $response = self::builtClient()->asJson()->post($url, $data);

        return self::handleResponse($response);
    }

    public static function refresh(User $user) : User
    {
        $response = self::refreshToken($user->refresh_token);
        $user->update([
            'token' => $response['access_token'],
            'refresh_token' => $response['refresh_token'],
            'expires_in' => $response['expires_in'],
            'expires_at' => ($response['expires_in'] ? now()->addSeconds($response['expires_in']) : null),
        ]);

        return $user;
    }

    public static function refreshToken(string $refresh_token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://exist.io/oauth2/access_token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
                'client_id'     => config('services.exist.client_id'),
                'client_secret' => config('services.exist.client_secret'),
            ]
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }

    protected static function handleResponse($response)
    {
        if ($response->successful()) {
            return $response;
        }

        switch ($response->status()) {
            case 404:
                return $response;
                break;
            case 429:
                sleep($response->header('retry-after') ?: 1);
                return self::get($url, $query);
                break;

            default:
                $response->throw();
                break;
        }
    }
}
