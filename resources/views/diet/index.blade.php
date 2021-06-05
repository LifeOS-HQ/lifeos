@extends('layouts.app')

@section('content')

    <h1>Ernährung</h1>
    <div class="mb-3">
        <a href="{{ \App\Models\Diet\Foods\Food::indexPath() }}" class="btn btn-secondary btn-sm">Nahrungsmittel</a>
        <a href="#" class="btn btn-secondary btn-sm">Tagebuch (TODO)</a>
        <a href="#" class="btn btn-secondary btn-sm">Pläne (TODO)</a>
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