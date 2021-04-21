<?php

namespace App\Http\Controllers\Widgets\Data\Health;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use App\Support\Chart\Chart;
use App\Support\Chart\Color;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WeightDevelopmentController extends Controller
{
    public function index(Request $request)
    {
        $chart = new Chart();

        $user = auth()->user();
        $options = $chart->forUser($user)
            ->startFrom($request->input('interval_unit'), $request->input('interval_count'))
            ->addYAxis([
                'title' => [
                    'text' => 'Stunden (h)',
                ],
            ])
            ->addSlug('energy', [
                'type' => 'column',
                'yAxis' => 0,
            ])
            ->addSlug('active_energy', [
                'type' => 'column',
                'yAxis' => 0,
            ])
            ->addSlug('weight', [
                'type' => 'column',
                'yAxis' => 0,
            ])
            ->base()->get();

        $interval_avgs = $chart->interval_avgs();
        $table = [
            'thead' => [],
            'tbody' => [],
        ];

        foreach ($interval_avgs['weight']['intervals'] as $interval_key => $interval) {

            if (Arr::has($interval_avgs['weight']['intervals'], $interval_key + 1)) {
                $interval_diff = (Carbon::createFromFormat('d.m.Y', $interval_avgs['weight']['intervals'][$interval_key]['date_formatted']))->diff(Carbon::createFromFormat('d.m.Y', $interval_avgs['weight']['intervals'][$interval_key + 1]['date_formatted']));
            }

            $table['tbody'][$interval_key]['date_formatted'] = $interval['date_formatted'];

            $table['tbody'][$interval_key]['weight_icon'] = '<i class="fas ' . $interval['icon_class'] . ' ' . $interval['font_color_class'] . '"></i>';
            $table['tbody'][$interval_key]['weight_avg_formatted'] = $interval['avg_formatted'];
            $table['tbody'][$interval_key]['weight_difference_absolute_formatted'] = $interval['difference_absolute_formatted'];

            $table['tbody'][$interval_key]['energy_icon'] = '<i class="fas ' . $interval_avgs['energy']['intervals'][$interval_key]['icon_class'] . ' ' . $interval_avgs['energy']['intervals'][$interval_key]['font_color_class'] . '"></i>';
            $table['tbody'][$interval_key]['energy_avg_formatted'] = $interval_avgs['energy']['intervals'][$interval_key]['avg_formatted'];
            $table['tbody'][$interval_key]['energy_difference_absolute_formatted'] = $interval_avgs['energy']['intervals'][$interval_key]['difference_absolute_formatted'];

            $table['tbody'][$interval_key]['active_energy_icon'] = '<i class="fas ' . $interval_avgs['active_energy']['intervals'][$interval_key]['icon_class'] . ' ' . $interval_avgs['active_energy']['intervals'][$interval_key]['font_color_class'] . '"></i>';
            $table['tbody'][$interval_key]['active_energy_avg_formatted'] = $interval_avgs['active_energy']['intervals'][$interval_key]['avg_formatted'];
            $table['tbody'][$interval_key]['active_energy_difference_absolute_formatted'] = $interval_avgs['active_energy']['intervals'][$interval_key]['difference_absolute_formatted'];

            $table['tbody'][$interval_key]['energy_balance_avg_formatted'] = number_format($interval['difference_absolute'] * 7000 / $interval_diff->days, 0, ',', '.');
        }

        $options['table'] = $table;

        return $options;
    }
}