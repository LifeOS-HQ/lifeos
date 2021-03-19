@extends('layouts.app')

@section('content')

    <div class="row align-items-center mb-3">
        <div class="col">
            <h1 class="col mb-0">Training > {{ $model->name }}</h1>
        </div>
        <div id="buttons" class="col-auto d-flex align-items-center justify-content-around">
            <a class="btn btn-secondary btn-sm ml-1" href="{{ route('workouts.histories.index', ['workout' => $model->id]) }}">Tagebuch</a>
            <form action="{{ route('workouts.histories.store', ['workout' => $model]) }}" class="ml-1" method="POST">
                @csrf

                <button type="submit" class="btn btn-success btn-sm">Starten</button>
            </form>
        </div>
    </div>

    <workout-exercises-index index-path="{{ \App\Models\Workouts\Exercises\Exercise::indexPath(['workout_id' => $model->id]) }}" :exercises="{{ json_encode($exercises) }}" :model="{{ json_encode($model) }}"></workout-exercises-index>

@endsection