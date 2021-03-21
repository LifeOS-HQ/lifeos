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
            ->base()->get();

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
                ],
            ],
            'tooltip' => [
                'pointFormat' => '<b>{point.avg:.1f}g</b>',
            ],
        ];

        return $options;
    }
}