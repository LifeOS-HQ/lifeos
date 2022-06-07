@extends('layouts.app')

@section('headline', \App\Models\Activities\Activity::label())

@section('content')

<activity-table index-path="{{ \App\Models\Activities\Activity::indexPath() }}" :lifeareas="{{ json_encode($lifeareas) }}"></activity-table>

@endsection