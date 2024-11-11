@extends('layouts.app')

@section('content')

<div class="d-flex">

    <h2 class="col pl-0">{{ \App\Models\Days\Day::label() }} > {{ $model->date_formatted }}</h2>
    <div class="text-right">
        <a class="btn btn-secondary btn-sm" href="{{ $model->index_path }}">Übersicht</a>
    </div>

</div>

<day-behaviour-history-index :model="{{ $model }}"></day-behaviour-history-index>

@endsection
