<?php

namespace App\Http\Controllers\Widgets\Health;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use App\Support\Chart\Chart;
use App\Support\Chart\Color;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class SleepController extends Controller
{
    public function index(Request $request)
    {
        $chart = new Chart();

        $user = auth()->user();
        $options = $chart->forUser($user)
            ->startFromWeeksAgo($request->input('weeks_count'))
            ->addYAxis([
                'title' => [
                    'text' => 'Stunden (h)',
                ],
            ])
            ->addSlug('sleep', [
                'type' => 'column',
                'yAxis' => 0,
            ])
            ->addSlug('time_in_bed', [
                'type' => 'column',
                'yAxis' => 0,
            ])
            ->build();

        $start = $chart->start_at();

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

        $options['table'] = [
            'time_in_bed_avg_formatted' => number_format($chart->avg('time_in_bed'), 2, ',', '.'),
            'sleep_avg_formatted' => number_format($chart->avg('sleep'), 2, ',', '.'),
            'sleep_start_avg_formatted' => $sleep_start_attribute->value($sleep_start_avg),
            'sleep_end_avg_formatted' => $sleep_end_attribute->value($sleep_end_avg),
        ];

        return $options;
    }
}