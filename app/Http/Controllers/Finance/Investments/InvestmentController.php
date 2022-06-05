<?php

namespace App\Http\Controllers\Finance\Investments;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class InvestmentController extends Controller
{
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'account_id' => 'required|integer',
            'investment_id' => 'required|integer',
            'date_formated' => 'required|date_format:d.m.Y',
            'security_price_formated' => 'required|formatted_number',
            'commission_formated' => 'required|formatted_number',
            'number_of_lots_formated' => 'required|formatted_number',
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
            'commission' => str_replace(',', '.', $attributes['commission_formated']),
            'numberOfLots' => str_replace(',', '.', $attributes['number_of_lots_formated']),
        ];

        $response = $rentabloApi->addLots($attributes);

        return $response;
    }
}
