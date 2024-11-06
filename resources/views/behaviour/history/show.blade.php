@extends('layouts.app')

@section('content')

<div class="d-flex">

    <h1 class="col pl-0">{{ \App\Models\Behaviours\Behaviour::label() }} > {{ $behaviour->name }} > {{ \App\Models\Behaviours\History::label(1) }} > {{ $model->end_at_formatted }}</h1>
    <div class="text-right">
        <a class="btn btn-secondary btn-sm" href="{{ $model->index_path }}">Übersicht</a>
    </div>

</div>

<div class="row">
    <div class="col-6">

        <div class="card">
            <div class="card-header">Allgemein</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-label"><b>Start</b></div>
                    <div class="col-value">{{ $model->start_at_formatted }}</div>
                </div>
                <div class="row">
                    <div class="col-label"><b>Ende</b></div>
                    <div class="col-value">{{ $model->end_at_formatted }}</div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
