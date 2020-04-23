<?php

namespace App\Http\Controllers\Home\Work;

use App\Http\Controllers\Controller;
use App\Models\Work\Month;
use App\Models\Work\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
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
    public function show()
    {
        $user = auth()->user();

        $date = DB::table('working_times')
                    ->select(DB::raw('DATE(start_at) AS date'), 'month_id')
                    ->where('user_id', $user->id)
                    ->latest('start_at')
                    ->first();

       $data = DB::table('working_times')
            ->select(DB::raw('SUM(seconds) AS seconds'), DB::raw('SUM(seconds_break) AS seconds_break'))
            ->where('user_id', $user->id)
            ->where(DB::raw('DATE(start_at)'), $date->date)
            ->groupBy('month_id')
            ->first();

        $month = Month::with('year')->find($date->month_id);

        $last_day_seconds = ($data->seconds - $data->seconds_break);
        $last_day_industryHours = Time::toIndustryHours($last_day_seconds);

        return [
            'last_day' => [
                'seconds' => $last_day_seconds,
                'industryHours' => $last_day_industryHours,
                'industryHours_formatted' => number_format($last_day_industryHours, 2, ',', '.'),
                'date_formatted' => (new Carbon($date->date))->format('d.m.Y'),
            ],
            'month_name' => $month->date->monthName,
            'statistics' => [
                'available_working_days' => $month->available_working_days,
                'available_hours_worked' => max($month->days_worked, $month->available_working_days) * ($month->hours_worked / $month->days_worked),
                'days_worked' => $month->days_worked,
                'hours_worked' => 1 * $month->hours_worked,
                'hours_worked_day' => ($month->hours_worked / min($month->days_worked, $month->workingdays_worked, $month->available_working_days)),
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
