<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Work\Year;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return auth()->user()->working_years()
                ->orderBy('year', 'DESC')
                ->get();
        }

        return view('work.year.index');
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
        $year = auth()->user()->working_years()->where('year', $year)->firstOrFail();

        $periods = new CarbonPeriod($year->date->startOfYear(), '1 months', $year->date->endOfYear());
        $categories = [];
        $planned_working_hours_month = [];
        $days_worked = [];
        $hours_worked = 0;

        $dates = [];
        foreach ($periods as $date) {
            $key = $date->format('Y-n');
            $categories[$key] = $date->monthName . ' ' . $date->year;
            $planned_working_hours_days[$key] = $year->planned_working_hours_day * 20;
            $days_worked[$key] = 0;
        }

        foreach ($year->months as $key => $month) {
            $key = $month->date->format('Y-n');
            $days_worked[$key] += $month->hours_worked;
            $planned_working_hours_days[$key] = $year->planned_working_hours_day * $month->available_working_days;
            $hours_worked += $month->hours_worked;
        }

        return [
            'categories' => array_values($categories),
            'series' => [
                [
                    'name' => 'Stunden',
                    'data' => array_values($days_worked),
                    'color' => '#28a745',
                    'type' => 'column',
                    'zones' => [
                        [
                            'value' => $month->year->planned_working_hours_day,
                            'color' => '#dc3545',
                        ],
                        [
                            'color' => '#28a745',
                        ],
                    ],
                ],
                [
                    'name' => 'Sollstunden',
                    'data' => array_values($planned_working_hours_days),
                    'type' => 'spline',
                    'tooltip' => [
                        'headerFormat' => '<b>{point.key}</b><br/>',
                        'pointFormat' => '{point.y:2f} h'
                    ],
                ],
            ],
            'title' => [
                'text' => 'Arbeitszeit im ' . $month->date->monthName
            ],
            'month_name' => $month->date->monthName,
            'statistics' => [
                'available_working_days' => $year->available_working_days,
                'available_hours_worked' => $year->available_working_days * ($year->hours_worked / $year->days_worked),
                'days_worked' => $year->days_worked,
                'hours_worked' => 1 * $year->hours_worked,
                'hours_worked_day' => ($year->hours_worked / $year->days_worked),
                'planned_working_hours' => ($year->planned_working_hours_day * $year->available_working_days),
                'planned_working_hours_day' => $year->planned_working_hours_day,
            ],
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
        //
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
