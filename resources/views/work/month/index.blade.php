@extends('layouts.app')

@section('content')

    <h1>Monate</h1>

    <work-month-table :years="{{ json_encode($years) }}"></work-month-table>

@endsection