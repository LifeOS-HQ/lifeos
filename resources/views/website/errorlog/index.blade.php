@extends('layouts.app')

@section('headline', 'Errorlog')

@section('buttons')
    <a href="{{ route('websites.index') }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

    @foreach ($websites as $website)
        <website-errorlog-show :model="{{ json_encode($website) }}"></website-errorlog-show>
    @endforeach

@endsection