@extends('layouts.app')

@section('content')

    <h1>Training > {{ $model->name }}</h1>

    @livewire('workouts.exercises.index', ['model' => $model])

@endsection