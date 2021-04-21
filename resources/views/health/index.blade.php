@extends('layouts.app')

@section('content')

    <h1>Gesundheit</h1>
    <div class="mb-3">

    </div>

    <div class="row">

        <div class="col-12">

            <widget-health-weight-development-show></widget-health-weight-development-show>

        </div>

    </div>

    <div class="row">

        <div class="col-12 col-lg-6">

            <widget-health-steps></widget-health-steps>

        </div>

        <div class="col-12 col-lg-6">

            <widget-health-sleep></widget-health-sleep>

            @livewire('health.index.nutrition')

        </div>

    </div>

@endsection