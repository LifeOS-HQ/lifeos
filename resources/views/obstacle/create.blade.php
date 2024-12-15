@extends('layouts.app')

@section('content')

    <div class="d-flex">

        <h2 class="col pl-0">{{ \App\Models\Obstacles\Obstacle::label() }}</h2>
        <div class="text-right">
            <a class="btn btn-secondary btn-sm" href="{{ \App\Models\Obstacles\Obstacle::indexPath() }}">Übersicht</a>
        </div>

    </div>

    <obstacle-create index-path="{{ \App\Models\Obstacles\Obstacle::indexPath() }}"></obstacle-create>

@endsection
