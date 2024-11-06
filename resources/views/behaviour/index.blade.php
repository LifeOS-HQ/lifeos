@extends('layouts.app')

@section('headline', \App\Models\Behaviours\Behaviour::label())

@section('content')

<behaviour-table index-path="{{ \App\Models\Behaviours\Behaviour::indexPath() }}"></behaviour-table>

@endsection
