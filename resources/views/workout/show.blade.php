@extends('layouts.app')

@section('content')

    <div class="row align-items-center mb-3">
        <h1 class="col mb-0">Training > {{ $model->name }}</h1>
        <div class="col-auto">
            <form action="{{ route('workouts.histories.store', ['workout' => $model]) }}" method="POST">
                @csrf

                <button type="submit" class="btn btn-success">Starten</button>
            </form>
        </div>
    </div>

    @livewire('workouts.exercises.index', ['model' => $model])

@endsection