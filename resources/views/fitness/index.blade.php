@extends('layouts.app')

@section('content')

    <h1>Fitness</h1>
    <div>
        <a href="{{ \App\Models\Workouts\Workout::indexPath() }}" class="btn btn-secondary btn-sm">{{ \App\Models\Workouts\Workout::label() }}</a>
        <a href="#" class="btn btn-secondary btn-sm">Tagebuch</a>
    </div>

    <h2>Widgets</h2>
    <div>anstehende Trainings</div>
    <div>absolvierte Training</div>
    <div>Anzahl trainings</div>
    <div>Aktivitätskalorien</div>


@endsection