<?php

namespace App\Http\Controllers\Diet\Plans;

use App\Http\Controllers\Controller;
use App\Models\Diet\Plans\Day;
use App\Models\Diet\Plans\Plan;
use Illuminate\Http\Request;

class DayController extends Controller
{
    protected $baseViewPath = 'diet.plan.day';

    public function __construct()
    {
        $this->authorizeResource(Day::class, 'day');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Plan $plan)
    {
        if ($request->wantsJson()) {
            return $plan->days()->orderBy('order_by', 'ASC')->get();
        }

        return view($this->baseViewPath . '.index');
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
    public function store(Request $request, Plan $plan)
    {
        $plan->loadCount([
            'days',
        ]);

        return $plan->days()->create([
            'user_id' => $plan->user_id,
            'order_by' => $plan->days_count + 1,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diet\Plans\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan, Day $day)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $day);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diet\Plans\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan, Day $day)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $day);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diet\Plans\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan, Day $day)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
        ]);

        $day->update($attributes);

        if ($request->wantsJson()) {
            return $day;
        }

        return redirect($day->path)
            ->with('status', [
                'type' => 'success',
                'text' => $day->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diet\Plans\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Plan $plan, Day $day)
    {
        if ($isDeletable = $day->isDeletable()) {
            $day->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $day->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $day->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($day->index_path)
            ->with('status', $status);
    }
}
