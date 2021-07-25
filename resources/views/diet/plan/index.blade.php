@extends('layouts.app')

@section('headline', \App\Models\Diet\Plans\Plan::label())

@section('buttons')
    <a href="{{ route('diet.index') }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

<diet-plan-table index-path="{{ \App\Models\Diet\Plans\Plan::indexPath() }}"></diet-plan-table>

@endsection