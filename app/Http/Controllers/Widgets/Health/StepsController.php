<?php

namespace App\Http\Controllers\Widgets\Health;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use App\Support\Chart\Chart;
use App\Support\Chart\Color;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class StepsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $start = now()->subDays((7 * $request->input('weeks_count')) -1);

        $end = now();
        $periods = new CarbonPeriod($start, '1 days', $end);

        $attribute_slugs = [
            'steps',
            'steps_active_min',
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
        $interval_length = 7;
        $interval_avgs = [];
        $interval_avgs_key = 0;
        foreach ($periods as $key => $period) {
            if ($key % $interval_length == 0) {
                $interval_avgs_key++;
            }
            $period->startOfDay();
            $day_key = $period->format('Y-m-d');
            $days[$day_key] = $period->format('d.m.Y'); // Categories
            foreach ($attributes as $attribute) {
                $value = $attribute->values->where('at', $period)->first();
                $data[$attribute->slug][$day_key] = (is_null($value) ? 0 : $attribute->value($value->raw) ?? 0);
                $interval_avgs[$attribute->slug]['name'] = $attribute->name;
                $interval_avgs[$attribute->slug]['intervals'][$interval_avgs_key]['date_formatted'] = $period->format('d.m.Y');
                $interval_avgs[$attribute->slug]['intervals'][$interval_avgs_key]['values'][] = $data[$attribute->slug][$day_key];
            }
        }

        $series = [];
        $plotlines = [];
        $avgs = [];
        $i = 0;
        foreach ($attributes as $slug => $attribute) {
            $color = $attribute->color;
            $series[] = [
                'cursor' => 'pointer',
                'name' => $attribute->name,
                'data' => array_values($data[$attribute->slug]),
                'color' => $color,
                'type' => 'column',
                'yAxis' => $i,
                'custom' => [
                    'slug' => $attribute->slug,
                ],
                // 'tooltip' => [
                //     'headerFormat' => '<b>{point.key}</b><br/>{series.name}<br/>',
                //     'pointFormat' => '{point.y:0.2f}'
                // ],
            ];
            $values_count = count($data[$attribute->slug]);
            $values_avg = array_sum($data[$attribute->slug]) / count(array_filter($data[$attribute->slug]));
            $avgs[$attribute->slug] = $values_avg;
            $series[] = [
                'name' => 'Ø ' . $attribute->name,
                'data' => array_fill(0, $values_count, $values_avg),
                'color' => $color,
                'type' => 'line',
                'yAxis' => $i,
                'tooltip' => [
                    'headerFormat' => '<b>{series.name}</b><br/>',
                    'pointFormat' => '{point.y:0.2f}'
                ],
                'marker' => [
                    'enabled' => false,
                ],
            ];

            $last_interval_avgs_key = 1;
            foreach ($interval_avgs[$attribute->slug]['intervals'] as $interval_avgs_key => &$interval) {
                $filtered_count = count(array_filter($interval['values']));
                $interval['avg'] = ($filtered_count == 0 ? 0 : array_sum($interval['values']) / $filtered_count);
                $interval['difference_absolute'] = round($interval['avg'] - $interval_avgs[$attribute->slug]['intervals'][$last_interval_avgs_key]['avg'], 2);
                $interval['difference_percentage'] = ($interval['avg'] == 0 ? 0 : round($interval['difference_absolute'] / $interval['avg'], 2));
                $interval['avg_formatted'] = number_format($interval['avg'], 2, ',', '.');
                $interval['difference_absolute_formatted'] = number_format($interval['difference_absolute'], 2, ',', '.');
                $interval['difference_percentage_formatted'] = number_format($interval['difference_percentage'] * 100, 0, ',', '.');
                if ($interval['difference_absolute'] > 0) {
                    $interval['font_color_class'] = 'text-success';
                    $interval['icon_class'] = 'fa-arrow-up';
                }
                elseif ($interval['difference_absolute'] < 0) {
                    $interval['font_color_class'] = 'text-danger';
                    $interval['icon_class'] = 'fa-arrow-down';
                }
                else {
                    $interval['font_color_class'] = 'text-warning';
                    $interval['icon_class'] = 'fa-arrow-right';
                }
                $last_interval_avgs_key = $interval_avgs_key;
            }
            $interval_avgs[$attribute->slug]['intervals'] = array_reverse($interval_avgs[$attribute->slug]['intervals']);

            $i++;
        }

        return [
            'chartOptions' => [
                'xAxis' => [
                    'categories' => array_values($days),
                ],
                'plotOptions' => [
                    'column' => [
                        'events' => [
                            'click' => 'function (event) { }',
                        ],
                    ],
                ],
                'series' => $series,
                'title' => [
                    'text' => '',
                ],
                'yAxis' => [
                    [
                        'title' => [
                            'text' => 'Schritte',
                        ],
                    ],
                    [
                        'title' => [
                            'text' => 'Aktive Zeit (min)',
                        ],
                        'opposite' => true,
                    ],
                ],
            ],
            'interval_avgs' => $interval_avgs,
        ];
    }
}
