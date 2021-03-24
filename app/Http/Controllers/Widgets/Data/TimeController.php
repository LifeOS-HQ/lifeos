<?php

namespace App\Http\Controllers\Widgets\Data;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use App\Models\Services\Data\Value;
use App\Models\Work\Time;
use App\Support\Chart\Chart;
use App\Support\Chart\Color;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class TimeController extends Controller
{
    public function index(Request $request)
    {
        $chart = new Chart();

        $user = auth()->user();

        $chart->forUser($user)
            ->startFrom($request->input('interval_unit'), $request->input('interval_count'));

        $chart->addEvent('settedAttributes', function (Chart $chart) {
            $leisure_min_attribute = new Attribute([
                'name' => 'Leisure',
                'slug' => 'leisure_min',
            ]);
            $leisure_min_attribute->values = new Collection();

            $minutes_in_day = (24 * 60);
            foreach ($chart->periods() as $key => $period) {
                $period = $period->startOfDay();
                $leisure_min_value = new Value([
                    'at' => $period,
                    'raw' => $minutes_in_day,
                ]);
                foreach ($chart->attributes() as $slug => $attribute) {
                    $value = $attribute->values->where('at', $period)->first();
                    $leisure_min_value->raw -= (is_null($value) ? 0 : ($value->raw ?? 0));
                }
                if ($leisure_min_value->raw == $minutes_in_day) {
                    $leisure_min_value->raw = 0;
                }
                $leisure_min_attribute->values->push($leisure_min_value);
            }

            $chart->addAttribute($leisure_min_attribute);
        });

        $working_times = Time::where('user_id', $user->id)
            ->whereDate('start_at', '>=', $chart->start_at())
            ->get();

        $working_min_attribute = new Attribute([
            'name' => 'Work Time',
            'slug' => 'working_min',
        ]);

        $working_mins = [];
        $working_min_attribute->values = new Collection();
        foreach ($working_times as $working_time) {
            $key = $working_time->start_at->format('Y-m-d');
            if (Arr::has($working_mins, $key)) {
                $working_mins[$key]['raw'] += ($working_time->seconds / 60);
            }
            else {
                $working_mins[$key] = [
                    'at' => $working_time->start_at->startOfDay(),
                    'raw' => ($working_time->seconds / 60),
                ];
            }
        }

        foreach ($working_mins as $working_min) {
            $working_min_attribute->values->push(new Value($working_min));
        }

        $chart->addAttribute($working_min_attribute);

        $makros_chart = [
            'chart' => [
                'type' => 'pie',
            ],
            'title' => [
                'text' => 'Zeit'
            ],
            'series' => [
                0 => [
                    'name' => 'Zeit',
                ],
            ],
            'tooltip' => [
                'pointFormat' => '<b>{point.avg:.1f} h</b>',
            ],
        ];

        $options = $chart->addSlug('time_in_bed', [])
            ->addSlug('workouts_min', [])
            ->pie($makros_chart)->get();

        return $options;
    }
}