<?php

namespace App\Apis\Habitica;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

/**
 * Verknüpfung zur Habitica API
 */
class Habitica
{
    const BASE_URL = 'https://habitica.com/api/v3/';

    protected $service_user_id;
    protected $token;

    public function __construct(\App\Models\Services\User $service_user)
    {
        $this->service_user_id = $service_user->service_user_id;
        $this->token = $service_user->token;
    }

    public static function login(string $username, string $password): Response
    {
        return Http::baseUrl(self::BASE_URL)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'x-client' => config('services.habitica.developer_user_id') . '-lifeOS',
            ])
            ->post('user/auth/local/login', [
                'username' => $username,
                'password' => $password,
            ]);
    }

    public function getUser(): Response
    {
        return $this->getClient()->get('user');
    }

    public function getTasks(array $query = []): Response
    {
        return $this->getClient()->get('tasks/user', []);
    }

    public function createTask(array $data): Response
    {
        return $this->getClient()->post('tasks/user', $data);
    }

    public function updateTask(string $id, array $data): Response
    {
        return $this->getClient()->put('tasks/user/' . $id, $data);
    }

    public function scoreTask(string $id, string $direction = 'up'): Response
    {
        return $this->getClient()->post('tasks/' . $id . '/score/' . $direction);
    }

    private function getClient(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'x-api-user' => $this->service_user_id,
                'x-api-key' => $this->token,
                'x-client' => config('services.habitica.developer_user_id') . '-lifeOS',
            ]);
    }

    public function cast(string $spell_id, string $target_id = ''): Response
    {
        return $this->getClient()->post('user/class/cast/' . $spell_id . ($target_id ? '?targetId=' . $target_id : ''));
    }
}

?>
