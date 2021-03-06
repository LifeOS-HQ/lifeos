<?php

namespace App\Http\Controllers;

use App\Models\Services\Data\Attributes\Attribute;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $now = now()->startOfDay();
        $start_of_year = $now->clone()->startOfYear();
        $end_of_year = $start_of_year->clone()->endOfYear();
        $days = new CarbonPeriod($start_of_year, '1 days', $end_of_year);

        $mood_attribute = Attribute::with([
                'values' => function ($query) use ($start_of_year) {
                    return $query->whereDate('at', '>=', $start_of_year);
                },
            ])->where('slug', 'mood')
            ->first();
        $mood_note_attribute = Attribute::with([
                'values' => function ($query) use ($start_of_year) {
                    return $query->whereDate('at', '>=', $start_of_year);
                },
            ])->where('slug', 'mood_note')
            ->first();

        $moods = [];
        foreach ($days as $key => $day) {
            $mood_day = $mood_attribute->values->where('at', $day)->first();
            $mood_note_day = $mood_note_attribute->values->where('at', $day)->first();
            if (is_null($mood_day)) {
                $moods[$day->format('Y-m-d')] = [
                    'bg_class' => 'bg-dark',
                    'mood' => 0,
                    'mood_note' => '',
                ];
                continue;
            }
            $moods[$day->format('Y-m-d')] = [
                'bg_class' => $mood_attribute->getBgClass($mood_day->raw),
                'mood' => $mood_day->raw,
                'mood_note' => $mood_note_day->raw,
            ];
        }

        return view('home')
            ->with('days', $days)
            ->with('month', 0)
            ->with('now', $now)
            ->with('days_over', $now->dayOfYear - 1)
            ->with('days_in_year', 365 + (int) $now->isLeapYear())
            ->with('moods', $moods);
    }
}
