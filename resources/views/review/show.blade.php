@extends('layouts.app')

@section('content')

<review-show :model="{{ json_encode($model) }}" :days="{{ json_encode($days) }}"></review-show>

@endsection