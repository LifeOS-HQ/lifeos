@extends('layouts.app')

@section('headline', 'Ernährung')

@section('content')

    <div class="mb-3">
        <a href="{{ \App\Models\Diet\Meals\Meal::indexPath() }}" class="btn btn-secondary btn-sm">{{ \App\Models\Diet\Meals\Meal::label() }}</a>
        <a href="{{ \App\Models\Diet\Foods\Food::indexPath() }}" class="btn btn-secondary btn-sm">Nahrungsmittel</a>
        <a href="{{ \App\Models\Diet\Diary\Day::indexPath() }}" class="btn btn-secondary btn-sm">Tagebuch</a>
        <a href="{{ \App\Models\Diet\Plans\Plan::indexPath() }}" class="btn btn-secondary btn-sm">Pläne</a>
    </div>

    <div class="row">

        <div class="col-12 col-lg-6">

            <widget-health-macro-calculator-show></widget-health-macro-calculator-show>

        </div>

        <div class="col-12 col-lg-6">

            <div>Mahlzeiten (heute)</div>
            <div>Kalorien Widget</div>
            <div>Macros (Bedarf erreicht?)</div>
            <div>Micros (Bedarf erreicht?)</div>

        </div>

    </div>


@endsection