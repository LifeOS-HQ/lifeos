@extends('layouts.app')

@section('content')

    <h1>Arbeitszeit</h1>

    <work-time-table :years="{{ json_encode($working_years) }}"></work-time-table>

@endsection