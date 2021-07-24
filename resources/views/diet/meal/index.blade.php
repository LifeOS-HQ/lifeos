@extends('layouts.app')

@section('content')

<diet-meal-table index-path="{{ route('diet.meals.index') }}"></diet-meal-table>

@endsection