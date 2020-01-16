<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Work\Month;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthController extends Controller
{
    protected $baseViewPath = 'work/month';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->wantsJson()) {
            return $user->working_months()
                ->year($request->input('year'))
                ->orderBy('date', 'DESC')
                ->get();
        }

        return view($this->baseViewPath . '.index')
            ->with('years', $user->working_years);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Work\Month  $month
     * @return \Illuminate\Http\Response
     */
    public function show(Month $month)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Work\Month  $month
     * @return \Illuminate\Http\Response
     */
    public function edit(Month $month)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Work\Month  $month
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Month $month)
    {
        $attributes = $request->validate([
            'bonus_formatted' => 'required|formatted_number',
            'net_formatted' => 'required|formatted_number',
        ]);

        $month->update($attributes);

        $month->year->cache()
            ->save();

        if ($request->wantsJson()) {
            return $month;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Work\Month  $month
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Month $month)
    {
        //
    }
}
