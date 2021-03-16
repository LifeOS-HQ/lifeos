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
        $chart = new Chart();
        return $chart->forUser(auth()->user())
            ->startFromWeeksAgo($request->input('weeks_count'))
            ->addSlug('steps', [
                'type' => 'column',
                'yAxis' => 0,
            ])
            ->addSlug('steps_active_min', [
                'type' => 'column',
                'yAxis' => 1,
            ])
            ->build();

    }
}
