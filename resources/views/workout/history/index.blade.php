@extends('layouts.app')

@section('content')

    <div class="row align-items-center mb-3">
        <h1 class="col mb-0">Training > <a href="{{ $model->path }}">{{ $model->name }}</a> > Tagebuch</h1>
        <div class="col-auto">
            <form action="{{ \App\Models\Workouts\History::indexPath(['workout_id' => $model->id]) }}" method="POST">
                @csrf

                <button type="submit" class="btn btn-success btn-sm">Starten</button>
            </form>
        </div>
    </div>

    <workout-histories-table index-path="{{ \App\Models\Workouts\History::indexPath(['workout_id' => $model->id]) }}"></workout-histories-table>

@endsection