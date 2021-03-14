@extends('layouts.app')

@section('content')

<activity-table index-path="{{ \App\Models\Activities\Activity::indexPath() }}" :lifeareas="{{ json_encode($lifeareas) }}"></activity-table>

@endsection