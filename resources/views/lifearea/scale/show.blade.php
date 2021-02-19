@extends('layouts.app')

@section('content')

<h1>Lifearea > <a class="text-body" href="{{ $lifearea->path }}">{{ $lifearea->title }}</a> > Level > {{ $model->value }}</h1>

@livewire('lifeareas.levels.goals.index', ['lifearea' => $lifearea, 'model' => $model])

@endsection