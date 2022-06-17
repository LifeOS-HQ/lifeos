<?php

namespace App\Http\Controllers\Widgets\Data\Health;

use App\Http\Controllers\Controller;
use App\Support\Chart\Chart;
use Illuminate\Http\Request;

class MeditationController extends Controller
{
    public function index(Request $request)
    {
        $chart = new Chart();

        $user = auth()->user();
        $options = $chart->forUser($user)
            ->startFrom($request->input('interval_unit'), $request->input('interval_count'))
            ->addYAxis([
                'title' => [
                    'text' => 'Meditation (min)',
                ],
            ])
            ->addSlug('meditation_min', [
                'type' => 'column',
                'dataLabels' => [
                    'enabled' => true,
                    'format' => '{point.y:,.2f}',
                ],
                'yAxis' => 0,
            ])
            ->base()->get();

        return $options;
    }
}