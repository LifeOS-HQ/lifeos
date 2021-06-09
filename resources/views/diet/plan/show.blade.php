@extends('layouts.app')

@section('headline', $model->label())

@section('content')

<diet-plan-day-table index-path="{{ $model->days_path }}" :model="{{ json_encode($model) }}"></diet-plan-day-table>

@endsection