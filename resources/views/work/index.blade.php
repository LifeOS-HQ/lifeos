@extends('layouts.app')

@section('content')

    <h1>Arbeit</h1>
    <div>
        <a href="{{ url('work/year') }}" class="btn btn-secondary btn-sm">Jahre</a>
        <a href="{{ url('work/month') }}" class="btn btn-secondary btn-sm">Monate</a>
        <a href="{{ url('work/time') }}" class="btn btn-secondary btn-sm">Zeiten</a>
    </div>

    <work-index class="mt-3"></work-index>

@endsection