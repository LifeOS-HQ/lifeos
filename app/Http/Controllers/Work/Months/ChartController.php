<?php

namespace App\Http\Controllers\Work\Months;

use App\Http\Controllers\Controller;
use App\Support\Holidays;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $year, int $month)
    {
        $year = auth()->user()
            ->working_years()
            ->where('year', $year)
            ->firstOrFail();

        $month = $year->months()
            ->where('month', $month)
            ->firstOrFail();

        $month->year = $year;
        $holidayDates = Holidays::dates($month->date->year, Holidays::LAND_NW);

        $periods = new CarbonPeriod($month->date->startOfMonth(), '1 days', $month->date->endOfMonth());
        $categories = [];
        $planned_working_hours_day = [];
        $days_worked = [];
        $hours_worked = 0;

        $dates = [];
        foreach ($periods as $date) {
            $isWorkingDay = true;
            if ($date->isWeekend() || $holidayDates->contains($date->format('Y-m-d'))) {
                $isWorkingDay = false;
            }
            $key = $date->format('Y-m-d');
            $categories[$key] = $date->format('d.m.Y');
            $planned_working_hours_days[$key] = ($isWorkingDay ? $month->year->planned_working_hours_day : 0);
            $days_worked[$key] = 0;
        }

        foreach ($month->times as $key => $time) {
            $days_worked[$time->start_at->format('Y-m-d')] += $time->industryHours;
            $hours_worked += $time->industryHours;
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
            'is_current_month' => $month->is_current_month,
            'statistics' => [
                'available_hours_worked' => (($month->available_working_days * $month->hours_worked_day) + $month->holiday_hours_worked),
                'available_working_days' => $month->available_working_days,
                'days_worked' => $month->days_worked,
                'gross' => $month->gross_formatted,
                'hours_worked' => 1 * $month->hours_worked,
                'hours_worked_day' => $month->hours_worked_day,
                'net' => $month->net_formatted,
                'planned_working_hours' => ($month->year->planned_working_hours_day * $month->available_working_days),
                'planned_working_hours_day' => $month->year->planned_working_hours_day,
            ],
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
