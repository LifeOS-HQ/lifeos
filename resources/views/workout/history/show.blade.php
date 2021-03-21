@extends('layouts.app')

@section('content')

    <div class="row align-items-center mb-3">
        <h1 class="col mb-0">Training > {{ $workout->name }} vom {{ $model->start_at->format('d.m.Y') }}</h1>
        <div class="col-auto d-flex align-items-center justify-content-around">
            <a class="btn btn-secondary btn-sm ml-1" href="{{ $model->index_path }}">Tagebuch</a>
            @if (is_null($model->end_at))
                <form action="{{ $model->path }}" class="ml-1" method="POST">
                    @csrf
                    @method('PUT')

                    <button type="submit" class="btn btn-danger btn-sm">Beenden</button>
                </form>
            @endif
            <form action="{{ $model->path }}" class="ml-1" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
            </form>
        </div>
    </div>

    <workout-histories-exercises-index index-path="{{ \App\Models\Workouts\Exercises\History::indexPath(['workout_history_id' => $model->id]) }}" :model="{{ json_encode($model) }}" :exercises="{{ json_encode($exercises) }}"></workout-histories-exercises-index>

@endsection