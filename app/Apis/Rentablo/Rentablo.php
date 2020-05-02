<?php

namespace App\Apis\Rentablo;

use Dasumi\Rentablo\Api;
use Illuminate\Support\Carbon;
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

    public function home($refresh = false) : array
    {
        if ($refresh) {
            Cache::forget('home.rentablo');
        }

        return Cache::remember('home.rentablo', self::CACHE_SECONDS, function () {
            return $this->homeData();
        });

    }

    protected function homeData()
    {
        $startOfYear = today()->startOfYear();

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
            'chart' => [],
            'year' => $startOfYear->year,
        ];


        $isAuthenticated = $this->api->authenticate($this->username, $this->password);

        // Alle Depots holen
        $accountIds = [];
        $accounts = $this->api->accounts->get();
        foreach ($accounts['accounts'] as $key => $account) {
            if ($account['type'] != '01_depot') {
                continue;
            }

            $accountId = $account['id'];
            $accountIds[] =  $accountId;

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

        $performance = $this->api->performance->depot($accountIds, '');

        $categories = [];
        $investedCapitals = [];
        $currentPortfolioValues = [];
        $maxPortfolioValue = 0;

        foreach ($performance['cashFlowAndPerformanceStatisticsList'][0]['cashFlowResults'] as $key => $cashFlowResult) {
            $categories[] = (new Carbon($cashFlowResult['date']))->format('d.m.Y');
            $investedCapitals[] = $cashFlowResult['investedCapital'];
            $currentPortfolioValues[] = $cashFlowResult['currentPortfolioValue'];

            $maxPortfolioValue = max($maxPortfolioValue, $cashFlowResult['currentPortfolioValue']);
            $currentPortfolioValue = $cashFlowResult['currentPortfolioValue'];
            $currentInvestedCapital = $cashFlowResult['investedCapital'];
        }

        $currentDifference = round($currentPortfolioValue - $currentInvestedCapital, 2);
        $currentDifferencePercent = round($currentDifference / $currentInvestedCapital * 100, 2);

        $data['value'] = [
            'currentPortfolioValueFormatted' => number_format($currentPortfolioValue, 2, ',', '.'),
            'currentInvestedCapitalFormatted' => number_format($currentInvestedCapital, 2, ',', '.'),
            'currentDifference' => $currentDifference,
            'currentDifferenceFormatted' => number_format($currentDifference, 2, ',', '.'),
            'currentDifferencePercentFormatted' => number_format($currentDifferencePercent, 2, ',', '.'),
            'maxPortfolioValueFormatted' => number_format($maxPortfolioValue, 2, ',', '.'),
        ];

        $data['chart'] = [
            'categories' => array_values($categories),
            'series' => [
                [
                    'name' => 'Kaufwert',
                    'data' => array_values($investedCapitals),
                    'color' => '#c42525',
                ],
                [
                    'name' => 'Marktwert',
                    'data' => array_values($currentPortfolioValues),
                    'color' => '#a6c96a',
                ],
            ],
            'title' => [
                'text' => 'Depotwert',
            ]
        ];

        return $data;
    }
}