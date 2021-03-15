<?php

namespace App\Http\Controllers\Widgets\Health;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use App\Support\Chart\Color;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class SleepController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $start = now()->subDays(30);
        $end = now();
        $periods = new CarbonPeriod($start, '1 days', $end);

        $sleep_start_attribute = Attribute::where('slug', 'sleep_start')->first();
        $sleep_start_avg = $sleep_start_attribute->values()
            ->where('user_id', $user->id)
            ->whereDate('at', '>=', $start)
            ->avg('raw');

        $sleep_end_attribute = Attribute::where('slug', 'sleep_end')->first();
        $sleep_end_avg = $sleep_end_attribute->values()
            ->where('user_id', $user->id)
            ->whereDate('at', '>=', $start)
            ->avg('raw');

        $attribute_slugs = [
            'sleep',
            'time_in_bed',
        ];

        $attributes = [];
        foreach ($attribute_slugs as $key => $slug) {
            $attributes[$slug] = Attribute::with([
                'values' => function ($query) use ($start, $user) {
                    return $query->where('user_id', $user->id)
                        ->whereDate('at', '>=', $start);
                },
            ])->where('slug', $slug)
            ->first();
        }

        $days = [];
        $data = [];
        foreach ($periods as $period) {
            $period->startOfDay();
            $key = $period->format('Y-m-d');
            $days[$key] = $period->format('d.m.Y'); // Categories
            foreach ($attributes as $attribute) {
                $value = $attribute->values->where('at', $period)->first();
                $data[$attribute->slug][$key] = (is_null($value) ? 0 : $attribute->value($value->raw) ?? 0);
            }
        }

        $series = [];
        $plotlines = [];
        $avgs = [];
        $i = 0;
        foreach ($attributes as $slug => $attribute) {
            $color = Color::random();
            $series[] = [
                'name' => $attribute->name,
                'data' => array_values($data[$attribute->slug]),
                'color' => $color,
                'type' => 'column',
                'yAxis' => 0,
                'tooltip' => [
                    'headerFormat' => '<b>{point.key}</b><br/>',
                    'pointFormat' => '{point.y:0.2f} Stunden'
                ],
            ];
            $values_count = count($data[$attribute->slug]);
            $values_avg = array_sum($data[$attribute->slug]) / count(array_filter($data[$attribute->slug]));
            $avgs[$attribute->slug] = $values_avg;
            $series[] = [
                'name' => 'Ø ' . $attribute->name,
                'data' => array_fill(0, $values_count, $values_avg),
                'color' => $color,
                'type' => 'line',
                'yAxis' => 0,
                'tooltip' => [
                    'headerFormat' => '<b>{series.name}</b><br/>',
                    'pointFormat' => '{point.y:0.2f} Stunden'
                ],
                'marker' => [
                    'enabled' => false,
                ],
            ];
            $i++;
        }

        return [
            'chartOptions' => [
                'xAxis' => [
                    'categories' => array_values($days),
                ],
                'series' => $series,
                'title' => [
                    'text' => '',
                ],
                'yAxis' => [
                    [
                        'title' => [
                            'text' => 'Stunden (h)',
                        ],
                        'plotLines' => $plotlines,
                    ],
                ],
            ],
            'table' => [
                'time_in_bed_avg_formatted' => number_format($avgs['time_in_bed'], 2, ',', '.'),
                'sleep_avg_formatted' => number_format($avgs['sleep'], 2, ',', '.'),
                'sleep_start_avg_formatted' => $sleep_start_attribute->value($sleep_start_avg),
                'sleep_end_avg_formatted' => $sleep_end_attribute->value($sleep_end_avg),
            ],
        ];
    }
}