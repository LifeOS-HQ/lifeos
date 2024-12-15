@extends('layouts.app')

@section('content')

    <div class="d-flex">

        <h2 class="col pl-0">{{ \App\Models\Obstacles\Obstacle::label() }} > {{ $model->wish }}</h2>
        <div class="text-right">
            <a class="btn btn-secondary btn-sm" href="{{ $model->path }}">Übersicht</a>
        </div>

    </div>

    <obstacle-edit :item="{{ $model }}"></obstacle-edit>

@endsection
