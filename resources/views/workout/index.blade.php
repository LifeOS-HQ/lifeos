@extends('layouts.app')

@section('content')

    <h1>Training</h1>
    <div class="mb-3">
        <a href="{{ url('workouts/exercises') }}" class="btn btn-secondary btn-sm">Übungen</a>
    </div>

    <workout-table index-path="{{ \App\Models\Workouts\Workout::indexPath() }}"></workout-table>

@endsection