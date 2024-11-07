@extends('layouts.app')

@section('content')

    <div class="row align-items-center mb-3">
        <div class="col">
            <h1 class="col mb-0 pl-0">Training > {{ $model->name }}</h1>
        </div>
        <div id="buttons" class="col-auto d-flex align-items-center justify-content-around">
            <a class="btn btn-secondary btn-sm ml-1" href="{{ \App\Models\Workouts\History::indexPath(['workout_id' => $model->id]) }}">{{ \App\Models\Workouts\History::label(1) }}</a>
            <form action="{{ \App\Models\Workouts\History::indexPath(['workout_id' => $model->id]) }}" class="ml-1" method="POST">
                @csrf

                <button type="submit" class="btn btn-success btn-sm">Starten</button>
            </form>
            <form action="{{ $model->path }}" class="ml-1" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
            </form>
        </div>
    </div>

    <workout-exercises-index index-path="{{ \App\Models\Workouts\Exercises\Exercise::indexPath(['workout_id' => $model->id]) }}" :exercises="{{ json_encode($exercises) }}" :model="{{ json_encode($model) }}"></workout-exercises-index>

@endsection
