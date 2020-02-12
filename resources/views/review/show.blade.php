@extends('layouts.app')

@section('content')

<review-show :model="{{ json_encode($model) }}"></review-show>

@endsection