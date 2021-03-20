<?php

namespace App\Http\Controllers\Widgets\Data\Health;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use App\Support\Chart\Chart;
use App\Support\Chart\Color;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class CaloriesController extends Controller
{
    public function index(Request $request)
    {
        $chart = new Chart();

        $user = auth()->user();
        $options = $chart->forUser($user)
            ->startFrom($request->input('interval_unit'), $request->input('interval_count'))
            ->addYAxis([
                'title' => [
                    'text' => 'Kalorien (kcal)',
                ],
            ])
            ->addSlug('energy', [
                'type' => 'line',
                'dataLabels' => [
                    'enabled' => true,
                    'format' => '{point.y:,.2f}',
                ],
                'yAxis' => 0,
            ])
            ->addSlug('active_energy', [
                'type' => 'line',
                'dataLabels' => [
                    'enabled' => true,
                    'format' => '{point.y:,.2f}',
                ],
                'yAxis' => 0,
            ])
            ->build();

        $start = $chart->start_at();

        $attributes = Attribute::with([
            ])->whereIn('slug', [
                'body_fat',
                'energy',
                'lean_mass',
                'weight',
            ])->get();

        $makros_chart = [
            'chart' => [
                'type' => 'pie',
            ],
            'title' => [
                'text' => 'Makros'
            ],
            'series' => [
                0 => [
                    'name' => 'Makros',
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
                ],
            ],
            'tooltip' => [
                'pointFormat' => '{series.name}: <b>{point.grams:.1f}g</b>',
            ],
        ];

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

        foreach ($nutrients as $key => $nutrient) {
            $nutrient->values_avg = $nutrient->values->avg('raw');
            $nutrient->calories_avg = $nutrient->values_avg * ($nutrient->slug == 'fat' ? 9 : 4);
            $makros_chart['series'][0]['data'][] = [
                'name' => $nutrient->name,
                'y' => $nutrient->calories_avg,
                'grams' => $nutrient->values_avg,
            ];
        }

        $options['makros_chart'] = $makros_chart;

        return $options;
    }
}