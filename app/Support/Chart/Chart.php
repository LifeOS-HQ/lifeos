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
     * Attributes for data
     * @var array
     */
    protected $attributes = [];

    /**
     * all days in the period used as categories for chart
     * key: Y-m-d
     * value: d.m.Y
     * @var array
     */
    protected $days = [];

    /**
     * values per attribute and day
     * @var array
     */
    protected $data = [];

    /**
     * Average per interval e.g. 1 day, week, month oder year
     * @var array
     */
    protected $interval_avgs = [];

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

    public function addAttribute(Attribute $attribute) : self
    {
        $this->attributes[$attribute->slug] = $attribute;

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

    public function base() : self
    {
        $this->build();

        $series = [];
        $i = 0;
        foreach ($this->attributes as $slug => $attribute) {
            $color = $attribute->color;
            $serie = array_merge([
                'cursor' => 'pointer',
                'name' => $attribute->name,
                'data' => array_values($this->data[$attribute->slug]),
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

            if ($serie['type'] != 'line') {
                $values_count = count($this->data[$attribute->slug]);
                $series[] = [
                    'name' => 'Ø ' . $attribute->name,
                    'data' => array_fill(0, $values_count, $this->avg($attribute->slug)),
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

            $i++;
        }

        $this->chart_options = [
            'chart' => [
                'zoomType' => 'x',
            ],
            'xAxis' => [
                'categories' => array_values($this->days),
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
        ];

        return $this;
    }

    public function pie(array $chart_options = []) : self
    {
        $this->build();

        $nutrients = Attribute::with([
                'values' => function ($query) {
                    return $query->where('user_id', auth()->user()->id)
                        ->latest('at')
                        ->take(30);
                },
            ])->whereIn('slug', [
                'carbohydrates',
                'fat',
                'protein',
            ])->get();

        $data = [];
        foreach ($this->attributes as $attribute) {
            $avg = $this->avg($attribute->slug);

            $data[] = [
                'name' => $attribute->name,
                'y' => $this->getPercentage($attribute, $avg),
                'avg' => $avg,
                'slug' => $attribute->slug,
            ];
        }

        $this->chart_options = array_merge([
            'chart' => [
                'type' => 'pie',
            ],
            'title' => [
                'text' => 'Titel'
            ],
            'series' => [
                0 => [
                    'name' => 'Serie',
                    'data' => [],
                ],
            ],
            'plotOptions' => [
                'pie' => [
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'dataLabels' => [
                        'enabled' => true,
                        'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                    ],
                    'events' => [
                        'click' => 'function (event) { }',
                    ],
                ],
            ],
            'tooltip' => [
                'pointFormat' => '{series.name}: <b>{point.avg:.1f}</b>',
            ],
        ], $chart_options);

        $this->chart_options['series'][0]['data'] = $data;

        return $this;
    }

    protected function getPercentage(Attribute $attribute, $avg)
    {
        if (! Arr::has($this->slugs, $attribute->slug)) {
            return $avg;
        }

        if (! Arr::has($this->slugs[$attribute->slug], 'percentage_callback')) {
            return $avg;
        }

        $percentage = $this->slugs[$attribute->slug]['percentage_callback']($attribute, $avg);
        Arr::forget($this->slugs[$attribute->slug], 'percentage_callback');

        return $percentage;
    }

    protected function setPeriods() : CarbonPeriod
    {
        $this->periods = new CarbonPeriod($this->start_at, '1 days', $this->end_at);

        return $this->periods;
    }

    protected function setPeriodIntervals() : array
    {
        $carbon_period_intervals = new CarbonPeriod($this->start_at, '1 ' . $this->interval['unit'], $this->end_at);

        $this->period_intervals = [];
        foreach ($carbon_period_intervals as $key => $date) {
            $this->period_intervals[] = $date->format('Y-m-d');
        }

        return $this->period_intervals;
    }

    protected function setAttributes() : array
    {
        foreach ($this->slugs as $slug => $serie) {
            $this->attributes[$slug] = Attribute::with([
                'values' => function ($query) {
                    return $query->where('user_id', $this->user->id)
                        ->whereDate('at', '>=', $this->start_at);
                },
            ])->where('slug', $slug)
            ->first();
        }

        return $this->attributes;
    }

    protected function setData() : void
    {
        $interval_avgs_key = 0;
        foreach ($this->periods as $key => $period) {
            if ($this->isNextPeriod($period)) {
                $interval_avgs_key++;
            }
            $period->startOfDay();
            $day_key = $period->format('Y-m-d');
            $this->days[$day_key] = $period->format('d.m.Y'); // Categories
            foreach ($this->attributes as $attribute) {
                $value = $attribute->values->where('at', $period)->first();
                $this->data[$attribute->slug][$day_key] = (is_null($value) ? 0 : $attribute->value($value->raw) ?? 0);
                $this->interval_avgs[$attribute->slug]['slug'] = $attribute->slug;
                $this->interval_avgs[$attribute->slug]['name'] = $attribute->name;
                $this->interval_avgs[$attribute->slug]['intervals'][$interval_avgs_key]['date_formatted'] = $period->format('d.m.Y');
                $this->interval_avgs[$attribute->slug]['intervals'][$interval_avgs_key]['values'][] = $this->data[$attribute->slug][$day_key];
            }
        }
    }

    public function isNextPeriod(Carbon $periode) : bool
    {
        return (in_array($periode->format('Y-m-d'), $this->period_intervals));
    }

    public function build() : self
    {
        $this->setPeriods();
        $this->setPeriodIntervals();

        $this->setAttributes();

        $this->setData($this->attributes);

        foreach ($this->attributes as $slug => $attribute) {

            $values_avg_count = count(array_filter($this->data[$attribute->slug]));
            $values_avg = ($values_avg_count == 0 ? 0 : array_sum($this->data[$attribute->slug]) / $values_avg_count);
            $this->avgs[$attribute->slug] = $values_avg;

            $last_interval_avgs_key = 1;
            foreach ($this->interval_avgs[$attribute->slug]['intervals'] as $interval_avgs_key => &$interval) {
                $filtered_count = count(array_filter($interval['values']));
                $interval['avg'] = ($filtered_count == 0 ? 0 : array_sum($interval['values']) / $filtered_count);
                $interval['difference_absolute'] = round($interval['avg'] - $this->interval_avgs[$attribute->slug]['intervals'][$last_interval_avgs_key]['avg'], 2);
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

            $this->interval_avgs[$attribute->slug]['intervals'] = array_reverse($this->interval_avgs[$attribute->slug]['intervals']);
        }

        return $this;
    }

    public function get() : array
    {
        return [
            'chartOptions' => $this->chart_options,
            'interval_avgs' => $this->interval_avgs,
        ];
    }

    public function options() : array
    {
        return $this->options;
    }

    public function start_at() : Carbon
    {
        return $this->start_at;
    }

    public function periods() : CarbonPeriod
    {
        return $this->periods;
    }

    public function avg(string $slug)
    {
        return Arr::get($this->avgs, $slug, null);
    }
}