<?php

namespace App\Apis\Rentablo;

use Dasumi\Rentablo\Api;
use Illuminate\Support\Facades\Cache;

class Rentablo
{
    const CACHE_SECONDS = 3600;

    protected $api;

    protected $username;
    protected $password;

    public function __construct(array $config)
    {
        $this->api = new Api($config['uri']);

        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    public function home() : array
    {
        return Cache::remember('home.rentablo', self::CACHE_SECONDS, function () {
            return $this->homeData();
        });

    }

    protected function homeData()
    {
        $data = [
            'accounts' => [],
            'dividends' => [
                'month' => [
                    'count' => 0,
                    'avg' => 0,
                ],
                'amount' => [
                    0 => 0,
                ],
            ],
            'valuations' => [
                0 => 0,
            ],
        ];

        $startOfYear = today()->startOfYear();

        $isAuthenticated = $this->api->authenticate($this->username, $this->password);

        // Alle Depots holen
        $accounts = $this->api->accounts->get();
        foreach ($accounts['accounts'] as $key => $account) {
            if ($account['type'] != '01_depot') {
                continue;
            }

            $accountId = $account['id'];

            $data['accounts'][$accountId] = $account;

            // Dividenden von diesem Jahr holen
            $dividends = $this->api->dividends->history($accountId, [], $startOfYear->format('Y-m-d'));
            $net = $dividends['nodesByYear'][$startOfYear->year]['netAmount'];
            $data['dividends']['amount'][$accountId] = $net;
            $data['dividends']['amount'][0] += $net;
            $data['dividends']['month']['count'] = count($dividends['nodesByYear'][$startOfYear->year]['children']);

            // Wert der Depots holen
            $valuation = $this->api->accounts->valuation($accountId, false);
            $data['valuations'][$accountId] = $valuation;
            $data['valuations'][0] += $valuation;
        }

        $data['dividends']['month']['avg'] = ($data['dividends']['amount'][0] / $data['dividends']['month']['count']);

        return $data;
    }
}