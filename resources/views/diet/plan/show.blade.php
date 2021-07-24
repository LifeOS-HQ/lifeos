@extends('layouts.app')

@section('headline', $model->label() . ' -> ' . $model->name)

@section('content')

<diet-plan-day-show index-path="{{ $model->days_path }}" :model="{{ json_encode($model) }}" :foods="{{ $foods }}"></diet-plan-day-show>

@endsection