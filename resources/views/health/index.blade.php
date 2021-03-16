@extends('layouts.app')

@section('content')

    <h1>Gesundheit</h1>
    <div class="mb-3">

    </div>

    <div class="row">

        <div class="col-12 col-lg-6">

            @livewire('health.index.nutrition')

            <widget-health-steps :attribute-slugs="{{ json_encode(['floors', 'sugar']) }}"></widget-health-steps>

        </div>

        <div class="col-12 col-lg-6">

            <widget-health-sleep></widget-health-sleep>

        </div>

    </div>

@endsection