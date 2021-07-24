@extends('layouts.app')

@section('content')

    <h1>Ernährung</h1>
    <div class="mb-3">
        <a href="{{ \App\Models\Diet\Meals\Meal::indexPath() }}" class="btn btn-secondary btn-sm">{{ \App\Models\Diet\Meals\Meal::label() }}</a>
        <a href="{{ \App\Models\Diet\Foods\Food::indexPath() }}" class="btn btn-secondary btn-sm">Nahrungsmittel</a>
        <a href="{{ \App\Models\Diet\Diary\Day::indexPath() }}" class="btn btn-secondary btn-sm">Tagebuch (WIP)</a>
        <a href="{{ \App\Models\Diet\Plans\Plan::indexPath() }}" class="btn btn-secondary btn-sm">Pläne (WIP)</a>
    </div>

    <div class="row">

        <div class="col-12 col-lg-6">

            <widget-health-macro-calculator-show></widget-health-macro-calculator-show>

        </div>

        <div class="col-12 col-lg-6">

            <div>Mahlzeiten</div>
            <div>Kalorien</div>
            <div>Macros</div>
            <div>Micros</div>

        </div>

    </div>


@endsection