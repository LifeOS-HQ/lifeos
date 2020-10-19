<?php

namespace App\Apis\Rentablo;

use App\Models\Work\Year;
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

        $performance = $this->api->performance->depot($accountIds, today()->subYear()->format('Y-m-d'));

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

    public function years($refresh = false) : array
    {
        if ($refresh) {
            Cache::forget('rentablo.years');
        }

        return Cache::remember('rentablo.years', self::CACHE_SECONDS, function () {
            return $this->yearsDate();
        });

    }

    public function yearsDate() : array
    {
        $data = [];

        $isAuthenticated = $this->api->authenticate($this->username, $this->password);

        $accountIds = [];
        $accounts = $this->api->accounts->get();
        foreach ($accounts['accounts'] as $key => $account) {
            if ($account['type'] != '01_depot') {
                continue;
            }

            $accountId = $account['id'];
            $accountIds[] =  $accountId;
        }

        $year = 0;
        $performance = $this->api->performance->depot($accountIds, '2015-01-01');
        $firstDay = new Carbon($performance['cashFlowAndPerformanceStatisticsList'][0]['cashFlowResults'][0]['date']);
        foreach ($performance['cashFlowAndPerformanceStatisticsList'][0]['cashFlowResults'] as $key => $cashFlowResult) {
            $date = (new Carbon($cashFlowResult['date']));
            if ($date->year != $year) {
                $year = $date->year;
                $data[$year] = [
                    'dividends' => [
                        'month' => [
                            'count' => 0,
                        ],
                        'net' => [
                            0 => 0,
                        ],
                        'net_formatted' => '0,00',
                    ],
                    'investedCapital' => [
                        'start' => ($key == 0 ? 0 : $cashFlowResult['investedCapital']),
                        'end' => $cashFlowResult['investedCapital'],
                        'diff' => 0,
                    ],
                    'wage' => [
                        'net_in_cents' => 0,
                    ],
                    'year' => $year,
                ];
            }

            if ($date->format('Y-m-d') == $date->endOfYear()->format('Y-m-d')) {
                $data[$year]['investedCapital']['end'] = $cashFlowResult['investedCapital'];
                $data[$year]['investedCapital']['diff'] = ($data[$year]['investedCapital']['end'] - $data[$year]['investedCapital']['start']);
                $data[$year]['investedCapital']['end_formatted'] = number_format($data[$year]['investedCapital']['end'], 2, ',', '.');
                $data[$year]['investedCapital']['start_formatted'] = number_format($data[$year]['investedCapital']['start'], 2, ',', '.');
                $data[$year]['investedCapital']['diff_formatted'] = number_format($data[$year]['investedCapital']['diff'], 2, ',', '.');
            }
        }

        $data[$year]['investedCapital']['end'] = $cashFlowResult['investedCapital'];
        $data[$year]['investedCapital']['diff'] = ($data[$year]['investedCapital']['end'] - $data[$year]['investedCapital']['start']);
        $data[$year]['investedCapital']['end_formatted'] = number_format($data[$year]['investedCapital']['end'], 2, ',', '.');
        $data[$year]['investedCapital']['start_formatted'] = number_format($data[$year]['investedCapital']['start'], 2, ',', '.');
        $data[$year]['investedCapital']['diff_formatted'] = number_format($data[$year]['investedCapital']['diff'], 2, ',', '.');

        foreach ($accountIds as $key => $accountId) {
            $dividends = $this->api->dividends->history($accountId, [], $firstDay);
            foreach ($dividends['nodesByYear'] as $year => $dividend) {
                $net = $dividend['netAmount'];
                $data[$year]['dividends']['net'][$accountId] = $net;
                $data[$year]['dividends']['net'][0] += $net;
                $data[$year]['dividends']['month']['count'] = count($dividend['children']);
                $data[$year]['dividends']['net_formatted'] = number_format($data[$year]['dividends']['net'][0], 2, ',', '.');
            }

        }

        $years = Year::all();
        foreach ($years as $key => $year) {
            $data[$year->year]['wage']['net_in_cents'] = $year->net_in_cents;
        }

        krsort($data);

        return array_values($data);
    }

    public function dividendsPerMonthDataAndInvestment(int $year, bool $refresh = false) : array
    {
        if ($refresh) {
            Cache::forget('rentablo.dividendsPerMonthDataAndInvestment.' . $year);
        }

        return Cache::remember('rentablo.dividendsPerMonthDataAndInvestment.' . $year, self::CACHE_SECONDS, function () use ($year) {
            return $this->dividendsPerMonthDataAndInvestmentData($year);
        });

    }

    public function dividendsPerMonthDataAndInvestmentData(int $year)
    {
        $data = [
            'dividends' => [],
            'investments' => [],
            'statistics' => [
                'sum' => 0,
                'sum_formatted' => '0,00',
                'sum_per_month' => [],
                'avg_per_month' => 0,
                'avg_per_month_formatted' => '0,00',
                'sum_per_investment' => [],
                'sum_per_investment_formatted' => [],
                'avg_per_investment' => [],
                'avg_per_investment_formatted' => [],
            ],
        ];



        $isAuthenticated = $this->api->authenticate($this->username, $this->password);

        $accountIds = [];
        $account_names = [];
        $accounts = $this->api->accounts->get();
        foreach ($accounts['accounts'] as $key => $account) {
            if ($account['type'] != '01_depot') {
                continue;
            }

            $account_id = $account['id'];
            $accountIds[] = $account_id;
            $account_names[$account_id] = $account['name'];
        }

        foreach ($accountIds as $account_id) {
            $dividends = $this->api->dividends->history($account_id, [], $year . '-01-01');
            for ($month_id = 0; $month_id < 12; $month_id++) {
                $data['statistics']['sum_per_month'][$month_id] = 0;
            }

            foreach ($dividends['investmentReferenceById'] as $investment_id => $value) {
                $data['investments'][$investment_id] = $dividends['investmentReferenceById'][$investment_id]['name'] . ' (' . $account_names[$account_id] . ')';
                $data['statistics']['sum_per_investment'][$investment_id] = 0;
                $data['statistics']['sum_per_investment_formatted'][$investment_id] = number_format($data['statistics']['sum_per_investment'][$investment_id], 2, ',', '.');
                $data['statistics']['avg_per_investment'][$investment_id] = 0;
                $data['statistics']['avg_per_investment_formatted'][$investment_id] = number_format($data['statistics']['avg_per_investment'][$investment_id], 2, ',', '.');
            }

            foreach ($dividends['nodesByYear'][$year]['investmentIds'] as $investment_id) {
                $data['dividends'][$investment_id] = [];
                for ($month_id = 0; $month_id < 12; $month_id++) {
                    $data['dividends'][$investment_id][$month_id] = 0;
                }
            }

            foreach ($dividends['nodesByYear'][$year]['children'] as $month_id => $month) {
                foreach ($month['children'] as $investment_id => $dividend) {
                    $data['dividends'][$investment_id][$month_id] += $dividend['netAmount'];
                    $data['statistics']['sum_per_month'][$month_id] += $dividend['netAmount'];
                    $data['statistics']['sum'] += $dividend['netAmount'];
                    $data['statistics']['sum_per_investment'][$investment_id] += $dividend['netAmount'];
                }
            }

            foreach ($dividends['investmentReferenceById'] as $investment_id => $value) {
                $data['statistics']['avg_per_investment'][$investment_id] = ($data['statistics']['sum_per_investment'][$investment_id] / 12);
                $data['statistics']['avg_per_investment_formatted'][$investment_id] = number_format($data['statistics']['avg_per_investment'][$investment_id], 2, ',', '.');
                $data['statistics']['sum_per_investment_formatted'][$investment_id] = number_format($data['statistics']['sum_per_investment'][$investment_id], 2, ',', '.');
            }
        }


        $data['statistics']['sum_formatted'] = number_format($data['statistics']['sum'], 2, ',', '.');
        $data['statistics']['avg_per_month'] = ($data['statistics']['sum'] / 12);
        $data['statistics']['avg_per_month_formatted'] = number_format($data['statistics']['avg_per_month'], 2, ',', '.');

        return $data;
    }
}