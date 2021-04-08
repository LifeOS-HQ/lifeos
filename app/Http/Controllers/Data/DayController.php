<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Services\Data\Attributes\Groups\Group;
use App\Support\Chart\Chart;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(?string $date_string = null)
    {
        if (is_null($date_string)) {
            $day = today();
        }
        else {
            $day = new Carbon($date_string);
        }
        $day->startOfDay();

        $groups = Group::whereNotIn('slug', [
            'custom',
            'mood',
            'location',
            'sleep',
        ])
            ->orderBy('name', 'ASC')
            ->get();

        return view('data.day.index')
            ->with('day', $day)
            ->with('groups', $groups);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $date_string, Group $group)
    {
        if (is_null($date_string)) {
            $day = today();
        }
        else {
            $day = new Carbon($date_string);
        }
        $day->startOfDay();

        $group->load([
            'attributes'
        ]);

        $avg_chart = new Chart();
        $avg_chart = $avg_chart->forUser(auth()->user())->addYAxis([
                'title' => [
                    'text' => 'Prozent [%]',
                ],
            ])->base()->get();
        $avg_chart['chartOptions']['xAxis']['categories'] = [];
        $avg_chart['chartOptions']['series'][0] = [
            'type' => 'column',
            'name' => 'Differenz Prozent',
            'data' => [],
            'tooltip' => [
                'headerFormat' => '<b>{point.x}</b><br/>',
                'pointFormat' => '<b>{point.date_formatted}: </b>{point.last_value:0.2f}<br /><b>Durchschnitt: </b>{point.avg:0.2f}<br/><b>Differenz: </b>{point.difference_absolute:0.2f} ({point.y:0.0f}%)'
            ],
        ];
        $avg_chart['chartOptions']['chart']['height'] = 250;
        $avg_chart['chartOptions']['legend']['enabled'] = false;
        foreach ($group->attributes as $key => $attribute) {
            $chart = new Chart();
            $chartOptions = $chart->forUser(auth()->user())
                ->day($day)
                ->startFrom($request->input('interval_unit'), $request->input('interval_count'))
                ->addYAxis([
                    'title' => [
                        'text' => '',
                    ],
                ])
                ->addSlug($attribute->slug, [
                    'type' => 'area',
                    'yAxis' => 0,
                ])
                ->base()->get();
                $chartOptions['chartOptions']['chart']['height'] = 100;
                $chartOptions['chartOptions']['title']['text'] = $attribute->name;
                $chartOptions['chartOptions']['legend']['enabled'] = false;
                $chartOptions['chartOptions']['xAxis']['labels']['enabled'] = false;
                $attribute->chart_options = $chartOptions['chartOptions'];
                $attribute->interval_avgs = $chartOptions['interval_avgs'];
                $last_value = $attribute->value($chart->attributes()[$attribute->slug]->values->last()->raw);
                $avg = $chart->avg($attribute->slug);
                $difference_absolute = round($avg - $last_value, 2);
                $difference_percentage = ($avg == 0 ? 0 : round($difference_absolute / $avg, 2)) * 100;
                $avg_chart['chartOptions']['xAxis']['categories'][] = $attribute->name;
                $avg_chart['chartOptions']['series'][0]['data'][] = [
                    'color' => $attribute->color,
                    'y' => $difference_percentage,
                    'avg' => $avg,
                    'last_value' => $last_value,
                    'difference_absolute' => $difference_absolute,
                    'date_formatted' => $day->format('d.m.Y'),
                ];
        }

        return [
            'attributes' => $group->attributes,
            'avg_chart' => $avg_chart['chartOptions'],
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
