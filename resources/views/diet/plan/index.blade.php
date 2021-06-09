@extends('layouts.app')

@section('headline', \App\Models\Diet\Plans\Plan::label())

@section('content')

<diet-plan-table index-path="{{ \App\Models\Diet\Plans\Plan::indexPath() }}"></diet-plan-table>

@endsection