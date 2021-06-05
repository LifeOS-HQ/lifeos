@extends('layouts.app')

@section('content')

<diet-food-table index-path="{{ route('diet.foods.index') }}"></diet-food-table>

@endsection