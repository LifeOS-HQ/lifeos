@extends('layouts.app')

@section('content')

    <h1>Tagebuch</h1>
    <div>
        <!-- <a href="{{ url('work/year') }}" class="btn btn-secondary btn-sm">Jahre</a> -->
    </div>

    <journal-index :activities="{{ json_encode($activities) }}"></journal-index>

@endsection