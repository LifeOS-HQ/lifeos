<?php

namespace App\Http\Controllers;

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

        return view('home')
            ->with('days', $days)
            ->with('month', 0)
            ->with('now', $now)
            ->with('days_over', $now->dayOfYear - 1)
            ->with('days_in_year', 365 + (int) $now->isLeapYear());
    }
}
