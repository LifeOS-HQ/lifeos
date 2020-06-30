@extends('layouts.app')

@section('content')

<activity-table :lifeareas="{{ json_encode($lifeareas) }}"></activity-table>

@endsection