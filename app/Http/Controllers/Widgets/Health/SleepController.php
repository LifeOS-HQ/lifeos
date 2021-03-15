<?php

namespace App\Http\Controllers\Widgets\Health;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class SleepController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $colors = [
            '#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'
        ];

        $start = now()->subDays(30);
        $end = now();
        $periods = new CarbonPeriod($start, '1 days', $end);

        $attribute_slugs = [
            'sleep',
            'time_in_bed',
            // 'sleep_start',
            // 'sleep_end',
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
        $i = 0;
        foreach ($attributes as $slug => $attribute) {
            $color = $colors[$i];
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

            ],
        ];
    }
}