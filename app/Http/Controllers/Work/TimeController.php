<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Work\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return auth()->user()->working_times()
                ->orderBy('start_at', 'DESC')
                ->where(DB::raw('YEAR(start_at)'), $request->input('year'))
                ->where(DB::raw('MONTH(start_at)'), $request->input('month'))
                ->get();
        }

        $working_years = auth()->user()->working_years;

        return view('work.time.index')
            ->with('working_years', $working_years);
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
        $attributes = $request->validate([
            'csv' => 'required',
        ]);

        $filename = 'betriko_arbeitszeit.csv';
        $zippedFilename = $filename . '.gz';

        $created = Storage::disk('local')->put($zippedFilename, base64_decode($attributes['csv']));

        if ($created === false) {
            return;
        }

        shell_exec('gunzip ' . storage_path('app/' . $filename));

        Artisan::call('work:time:import');

        return 'test';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Work\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function show(Time $time)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Work\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function edit(Time $time)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Work\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Time $time)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Work\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function destroy(Time $time)
    {
        //
    }
}
