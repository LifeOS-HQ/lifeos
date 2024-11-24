<?php

namespace App\SocialiteProviders\Exist;

use Exception;
use App\Apis\Exist\Http;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\User;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\Contracts\OAuth2\ProviderInterface;

class ExistProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'activity_read productivity_read mood_read sleep_read workouts_read events_read finance_read food_read food_write health_read health_write location_read media_read social_read weather_read custom_read manual_read',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://exist.io/oauth2/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://exist.io/oauth2/access_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://exist.io/api/2/accounts/profile/';

        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions($token)
        );

        $user = json_decode($response->getBody(), true);

        Http::setAccessToken($token);
        Http::post('attributes/acquire/', array_reduce(Http::PROVIDED_ATTRIBUTES, function ($carry, $attribute_slug) {
            $carry[] = [
                'name' => $attribute_slug,
            ];

            return $carry;
        }, []));

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['username'],
            'nickname' => $user['username'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => null,
            'avatar' => $user['avatar'],
        ]);
    }

    /**
     * Get the default options for an HTTP request.
     *
     * @param string $token
     * @return array
     */
    protected function getRequestOptions($token)
    {
        return [
            'headers' => [
                // 'Accept' => 'application/vnd.deere.axiom.v3+json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
