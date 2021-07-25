@extends('layouts.app')

@section('headline', \App\Models\Diet\Foods\Food::label())

@section('buttons')
    <a href="{{ route('diet.index') }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

<diet-food-table index-path="{{ \App\Models\Diet\Foods\Food::indexPath() }}"></diet-food-table>

@endsection