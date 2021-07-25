@extends('layouts.app')

@section('headline', \App\Models\Diet\Meals\Meal::label())

@section('buttons')
    <a href="{{ route('diet.index') }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

<diet-meal-table index-path="{{ \App\Models\Diet\Meals\Meal::indexPath() }}"></diet-meal-table>

@endsection