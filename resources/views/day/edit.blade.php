@extends('layouts.app')

@section('content')

    <div class="d-flex">

        <h2 class="col pl-0">{{ \App\Models\Days\Day::label() }} > {{ $model->date_formatted }} - Edit</h2>
        <div class="text-right">
            <a class="btn btn-secondary btn-sm" href="{{ $model->path }}">Tag</a>
        </div>

    </div>

    <day-edit
        :day="{{ json_encode($model) }}"
        :initial-behaviours="{{ json_encode($behaviours) }}"
    ></day-edit>

@endsection
