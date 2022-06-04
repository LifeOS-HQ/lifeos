<?php

namespace App\Http\Controllers\Finance\Dividends;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class DividendController extends Controller
{
    public function index(Request $request)
    {
        $rentabloApi = App::make('RentabloApi');
        if (is_null($rentabloApi)) {
            return [];
        }

        $response = $rentabloApi->getInvestments();

        $accounts = [];
        foreach (Arr::get($response, 'investments', []) as $investment) {
            $account_id = $investment['account']['id'];
            if (! Arr::has($accounts, $account_id)) {
                $accounts[$account_id] = $investment['account'];
                $accounts[$account_id]['investments'] = [];
            }
            Arr::forget($investment, 'account');
            $accounts[$account_id]['investments'][] = $investment;
        }

        return [
            'accounts' => $accounts,
        ];
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'account_id' => 'required|integer',
            'investment_id' => 'required|integer',
            'date_formated' => 'required|date_format:d.m.Y',
            'security_price_formated' => 'required|formatted_number',
            'tax_amount_formated' => 'required|formatted_number',
        ]);

        $rentabloApi = App::make('RentabloApi');
        if (is_null($rentabloApi)) {
            return [];
        }

        $attributes = [
            'account_id' => $attributes['account_id'],
            'investment_id' => $attributes['investment_id'],
            'date' => (Carbon::createfromFormat('d.m.Y', $attributes['date_formated']))->format('Y-m-d\TH:i:s.n\Z'),
            'securityPrice' => str_replace(',', '.', $attributes['security_price_formated']),
            'taxAmount' => str_replace(',', '.', $attributes['tax_amount_formated']),
        ];

        $response = $rentabloApi->addDividend($attributes);

        return $response;
    }
}
