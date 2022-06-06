<?php

namespace App\Apis\Habitica;

/**
 * Verknüpfung zur Habitica API
 */
class Habitica
{
    const BASE_URL = 'https://habitica.com/api/v3/';

    protected $username;
    protected $password;

    public function __construct(\App\Models\Services\User $service_user)
    {
        $this->username = $service_user->username;
        $this->password = $service_user->password;
    }

    protected function headers(): array
    {
        return [
            'Content-Type: application/json',
            'x-api-user: ' . $this->username,
            'x-api-key: ' . $this->password,
            'x-client: ' . $this->username . '-lifeOS',
        ];
    }

    public function getUser(): array
    {
        $url = self::BASE_URL . 'user';
        $headers = $this->headers();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);

        return $response;
    }

    public function cast(string $spell_id, string $target_id = '')
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => self::BASE_URL . 'user/class/cast/' . $spell_id . ($target_id ? '?targetId=' . $target_id : ''),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Content-Length: 0',
                'Content-Type: application/json',
                'x-api-user: ' . $this->username,
                'x-api-key: ' . $this->password,
                'x-client: ' . $this->username . '-lifeOS',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);

        return $response;
    }
}

?>