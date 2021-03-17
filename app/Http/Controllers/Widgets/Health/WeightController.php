<?php

namespace App\Http\Controllers\Widgets\Health;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;
use App\Support\Chart\Chart;
use App\Support\Chart\Color;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class WeightController extends Controller
{
    public function index(Request $request)
    {
        $chart = new Chart();

        $user = auth()->user();
        $options = $chart->forUser($user)
            ->startFrom($request->input('interval_unit'), $request->input('interval_count'))
            ->addYAxis([
                'title' => [
                    'text' => 'Gewicht (kg)',
                ],
            ])
            ->addYAxis([
                'title' => [
                    'text' => 'Körperfett (%)',
                ],
                'opposite' => true,
            ])
            ->addSlug('weight', [
                'type' => 'line',
                'yAxis' => 0,
                'dataLabels' => [
                    'enabled' => true,
                    'format' => '{point.y:,.2f}',
                ],
                'zIndex' => 5
            ])
            ->addSlug('body_fat', [
                'type' => 'column',
                'yAxis' => 1,
                'zIndex' => 1
            ])
            ->addSlug('lean_mass', [
                'type' => 'line',
                'yAxis' => 0,
                'zIndex' => 4,
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

        $body_fat_attribute = $attributes->where('slug', 'body_fat')->first();
        $body_fat_avg = $body_fat_attribute->values()->where('user_id', auth()->user()->id)->whereDate('at', '>=', now()->subDays(7))->whereDate('at', '<', now())->avg('raw');

        $weight_attribute = $attributes->where('slug', 'weight')->first();
        $current_weight_avg = $weight_attribute->values()->where('user_id', auth()->user()->id)->whereDate('at', '>=', now()->subDays(7))->whereDate('at', '<', now())->avg('raw');
        $last_weight_avg = $weight_attribute->values()->where('user_id', auth()->user()->id)->whereDate('at', '>=', now()->subDays(14))->whereDate('at', '<', now()->subDays(7))->avg('raw');

        $weight_difference = $current_weight_avg - $last_weight_avg;
        $weight_difference_kcal = $weight_difference / 7 * 7000;

        $weight_difference_goal = $body_fat_avg / 20 / 2 * $current_weight_avg * -1;
        $weight_difference_goal_kcal = ($weight_difference_goal - $weight_difference) * 7000 / 7;

        $energy_attribute = $attributes->where('slug', 'energy')->first();
        $energy_avg = $energy_attribute->value($energy_attribute->values()->where('user_id', auth()->user()->id)->whereDate('at', '>=', now()->subDays(7))->avg('raw'));
        $energy_avg_goal = $energy_avg + $weight_difference_goal_kcal;

        $options['table'] = [
            'last_weight_avg_formatted' => number_format($last_weight_avg, 2, ',', '.'),
            'current_weight_avg_formatted' => number_format($current_weight_avg, 2, ',', '.'),
            'weight_difference_formatted' => number_format($weight_difference, 2, ',', '.'),
            'weight_difference_goal_formatted' => number_format($weight_difference_goal, 2, ',', '.'),
            'weight_difference_goal_difference_formatted' => number_format($weight_difference_goal - $weight_difference, 2, ',', '.'),
            'weight_difference_kcal_formatted' => number_format($weight_difference_kcal, 2, ',', '.'),
            'weight_difference_goal_kcal_formatted' => number_format($weight_difference_goal_kcal, 2, ',', '.'),
            'weight_difference_goal_kcal_difference_formatted' => number_format($weight_difference_goal_kcal - $weight_difference_kcal, 2, ',', '.'),
            'energy_avg_formatted' => number_format($energy_avg, 0, ',', '.'),
            'energy_avg_goal_formatted' => number_format($energy_avg_goal, 0, ',', '.'),
            'energy_avg_goal_diference_formatted' => number_format($energy_avg_goal - $energy_avg, 0, ',', '.'),
        ];

        return $options;
    }
}