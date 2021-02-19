@extends('layouts.app')

@section('content')

    <div class="row align-items-center mb-3">
        <h1 class="col mb-0">Training > {{ $workout->name }} vom {{ $model->start_at->format('d.m.Y') }}</h1>
        <div class="col-auto">
            <a class="btn btn-secondary" href="{{ route('workouts.histories.index', ['workout' => $workout->id]) }}">Tagebuch</a>
        </div>
        @if (is_null($model->end_at))
            <div class="col-auto">
                <form action="{{ $model->path }}" method="POST">
                    @csrf
                    @method('PUT')

                    <button type="submit" class="btn btn-danger">Beenden</button>
                </form>
            </div>
        @endif
    </div>

    @livewire('workouts.histories.show', ['workout_history' => $model, 'workout' => $workout])

@endsection