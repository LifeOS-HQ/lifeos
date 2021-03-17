<?php

namespace App\Support\Chart;

use App\Models\Services\Data\Attributes\Attribute;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;

class Chart
{
    protected $start_at;
    protected $end_at;
    protected $user;
    protected $yAxis = [];
    protected $interval = [
        'count' => 4,
        'unit' => 'weeks',
    ];

    /**
     * avg of all attribute values
     * key is attribute slug
     * @var array
     */
    protected $avgs = [];

    /**
     * holds the chart options for highcharts
     * @var array
     */
    protected $options = [];

    /**
     * holds the slugs and their options
     * @var array
     */
    protected $slugs = [];

    public function __construct()
    {
        $this->end_at = now();
    }

    public function startFromWeeksAgo(int $weeks_count) : self
    {
        $this->start_at = now()->subWeeks($weeks_count);

        return $this;
    }

    public function startFrom(string $interval_unit, int $interval_count)
    {
        $this->interval = [
            'count' => $interval_count,
            'unit' => $interval_unit,
        ];
        $this->start_at = now()->sub($interval_unit, $interval_count);

        return $this;
    }

    public function forUser(User $user) : self
    {
        $this->user = $user;

        return $this;
    }

    public function addSlug(string $slug, array $options = []) : self
    {
        $this->slugs[$slug] = $options;

        return $this;
    }

    public function addYAxis(array $options) : self
    {
        $this->yAxis[] = $options;

        return $this;
    }

    public function build() : array
    {
        $periods = new CarbonPeriod($this->start_at, '1 days', $this->end_at);
        $carbon_period_intervals = new CarbonPeriod($this->start_at, '1 ' . $this->interval['unit'], $this->end_at);

        $period_intervals = [];
        foreach ($carbon_period_intervals as $key => $date) {
            $period_intervals[] = $date->format('Y-m-d');
        }

        $attributes = [];
        foreach ($this->slugs as $slug => $serie) {
            $attributes[$slug] = Attribute::with([
                'values' => function ($query) {
                    return $query->where('user_id', $this->user->id)
                        ->whereDate('at', '>=', $this->start_at);
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
            // dump($period->format('Y-m-d'), $period_intervals, in_array($period->format('Y-m-d'), $period_intervals));
            if (in_array($period->format('Y-m-d'), $period_intervals)) {
                $interval_avgs_key++;
            }
            $period->startOfDay();
            $day_key = $period->format('Y-m-d');
            $days[$day_key] = $period->format('d.m.Y'); // Categories
            foreach ($attributes as $attribute) {
                $value = $attribute->values->where('at', $period)->first();
                $data[$attribute->slug][$day_key] = (is_null($value) ? 0 : $attribute->value($value->raw) ?? 0);
                $interval_avgs[$attribute->slug]['slug'] = $attribute->slug;
                $interval_avgs[$attribute->slug]['name'] = $attribute->name;
                $interval_avgs[$attribute->slug]['intervals'][$interval_avgs_key]['date_formatted'] = $period->format('d.m.Y');
                $interval_avgs[$attribute->slug]['intervals'][$interval_avgs_key]['values'][] = $data[$attribute->slug][$day_key];
            }
        }

        $series = [];
        $i = 0;
        foreach ($attributes as $slug => $attribute) {
            $color = $attribute->color;
            $serie = array_merge([
                'cursor' => 'pointer',
                'name' => $attribute->name,
                'data' => array_values($data[$attribute->slug]),
                'color' => $color,
                'custom' => [
                    'slug' => $slug,
                ],
                // 'tooltip' => [
                //     'headerFormat' => '<b>{point.key}</b><br/>{point.series.options.custom.slug}<br/>',
                //     'pointFormat' => '{point.y:0.2f}'
                // ],
            ], $this->slugs[$slug]);
            $series[] = $serie;
            $values_count = count($data[$attribute->slug]);
            $values_avg_count = count(array_filter($data[$attribute->slug]));
            $values_avg = ($values_avg_count == 0 ? 0 : array_sum($data[$attribute->slug]) / $values_avg_count);
            $this->avgs[$attribute->slug] = $values_avg;

            if ($serie['type'] != 'line') {
                $series[] = [
                    'name' => 'Ø ' . $attribute->name,
                    'data' => array_fill(0, $values_count, $values_avg),
                    'color' => $color,
                    'type' => 'line',
                    'yAxis' => $serie['yAxis'],
                    'tooltip' => [
                        'headerFormat' => '<b>{series.name}</b><br/>',
                        'pointFormat' => '{point.y:0.2f}'
                    ],
                    'marker' => [
                        'enabled' => false,
                    ],
                ];
            }

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

        $this->options = [
            'chartOptions' => [
                'chart' => [
                    'zoomType' => 'x',
                ],
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
                'yAxis' => $this->yAxis,
            ],
            'interval_avgs' => $interval_avgs,
        ];

        return $this->options;
    }

    public function options() : array
    {
        return $this->options;
    }

    public function start_at() : Carbon
    {
        return $this->start_at;
    }

    public function avg(string $slug)
    {
        return Arr::get($this->avgs, $slug, null);
    }
}