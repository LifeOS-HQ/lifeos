<?php

namespace App\Http\Controllers\Home\Mood;

use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Attribute;

class MoodController extends Controller
{
    public function show(Request $request)
    {
        $now = now()->startOfDay();
        $start_of_year = $now->clone()->startOfYear();
        $end_of_year = $start_of_year->clone()->endOfYear();
        $days = new CarbonPeriod($start_of_year, '1 days', $end_of_year);

        $mood_attribute = Attribute::with([
                'values' => function ($query) use ($start_of_year) {
                    return $query->where('user_id', auth()->user()->id)
                        ->whereDate('at', '>=', $start_of_year);
                },
            ])->where('slug', 'mood')
            ->first();
        $mood_note_attribute = Attribute::with([
                'values' => function ($query) use ($start_of_year) {
                    return $query->where('user_id', auth()->user()->id)
                        ->whereDate('at', '>=', $start_of_year);
                },
            ])->where('slug', 'mood_note')
            ->first();

        $moods = [];
        $mood_day = null;
        foreach ($days as $day) {
            if (! is_null($mood_attribute)) {
                $mood_day = $mood_attribute->values->where('at', $day)->first();
            }
            if (! is_null($mood_note_attribute)) {
                $mood_note_day = $mood_note_attribute->values->where('at', $day)->first();
            }

            if ($day > $now) {
                $moods[$day->format('Y-m-d')] = [
                    'bg_class' => 'bg-light',
                    'mood' => 0,
                    'mood_note' => 'test',
                ];
                continue;
            }

            if (is_null($mood_day)) {
                $moods[$day->format('Y-m-d')] = [
                    'bg_class' => 'bg-dark',
                    'mood' => 0,
                    'mood_note' => 'test',
                ];
                continue;
            }

            $moods[$day->format('Y-m-d')] = [
                'bg_class' => $mood_attribute->getBgClass($mood_day->raw),
                'mood' => $mood_day->raw,
                'mood_note' => nl2br($mood_note_day->raw),
            ];
        }

        $days_over = 0;
        $days_left = 0;
        $last_month = null;
        $months = [];
        foreach ($days as $day) {
            if ($last_month != $day->month) {

                if ($now->month > $day->month) {
                    $days_over = $day->daysInMonth;
                    $days_left = 0;
                }
                elseif ($now->month < $day->month) {
                    $days_over = 0;
                    $days_left = $day->daysInMonth;
                }
                else {
                    $days_over = $now->day;
                    $days_left = $day->daysInMonth - $now->day;
                }

                $months[$day->month - 1] = [
                    'name' => $day->monthName,
                    'days_over' => $days_over,
                    'days_left' => $days_left,
                    'show_days_left' => ($now->month == $day->month && $days_left > 0),
                    'days' => [],
                ];

                $last_month = $day->month;
            }

            $months[$day->month - 1]['days'][] = [
                'day' => $day->day,
                'mood' => Arr::get($moods, $day->format('Y-m-d')),
                'name' => $day->dayName,
                'formatted' => $day->format('d.m.Y'),
                'path' => route('data.day.index', [
                    'date_string' => $day->format('Y-m-d')
                ]),
            ];
        }

        return [
            'months' => $months,
            'days_over' => $now->dayOfYear - 1,
            'days_in_year' => 365 + (int) $now->isLeapYear(),
        ];
    }
}
