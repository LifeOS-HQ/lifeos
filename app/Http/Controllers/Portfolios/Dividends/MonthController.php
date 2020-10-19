<?php

namespace App\Http\Controllers\Portfolios\Dividends;

use App\Http\Controllers\Controller;
use App\Models\Work\Year;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MonthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Work\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function show(int $year)
    {
        $rentabloApi = App::make('RentabloApi');
        $data = $rentabloApi->dividendsPerMonthDataAndInvestment($year);

        $categories = [
            1,2,3,4,5,6,7,8,9,10,11,12
        ];

        natsort($data['investments']);

        $series = [];
        foreach ($data['investments'] as $isin => $name) {
            $series[] = [
                'name' => $name,
                'data' => array_values($data['dividends'][$isin]),
                'type' => 'column',
                'yAxis' => 0,
            ];
        }

        $series[] = [
            'name' => 'Durchschnitt',
            'data' => array_fill(0, 12, $data['statistics']['avg_per_month']),
            'type' => 'spline',
            'yAxis' => 0,
        ];

        $series[] = [
            'name' => '1. Ziel',
            'data' => array_fill(0, 12, 500),
            'type' => 'spline',
            'yAxis' => 0,
        ];


        return [
            'categories' => $categories,
            'series' => $series,
            'title' => [
                'text' => 'Dividenden in ' . $year,
            ],
            'statistics' => $data['statistics'],
            'investments' => $data['investments'],
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Work\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function edit(Year $year)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Work\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Year $year)
    {
        $attributes = $request->validate([
            'planned_working_hours_formatted' => 'required|formatted_number',
            'tax_refund_formatted' => 'required|formatted_number',
            'wage_bonus_formatted' => 'required|formatted_number',
            'wage_formatted' => 'required|formatted_number',
        ]);

        $year->update($attributes);
        $year->cacheMonths();
        $year->cache()->save();

        if ($request->wantsJson()) {
            return $year;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Work\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function destroy(Year $year)
    {
        //
    }
}
