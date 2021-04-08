@extends('layouts.app')

@section('content')

    <h1>Fitness</h1>
    <div>
        <a href="{{ \App\Models\Workouts\Workout::indexPath() }}" class="btn btn-secondary btn-sm">{{ \App\Models\Workouts\Workout::label() }}</a>
        <a href="#" class="btn btn-secondary btn-sm">Tagebuch</a>
        <a href="{{ route('widgets.user.index', ['view' => 'fitness-index']) }}" class="btn btn-secondary btn-sm">Widgets</a>
    </div>

    <h2>Widgets</h2>
    <div>anstehende Trainings</div>
    <div>absolvierte Training</div>
    <div>Anzahl trainings</div>
    <div>Aktivitätskalorien</div>

    <widget-index view="fitness.index" :widgets="{{ json_encode($widgets) }}"></widget-index>


@endsection