@extends('layouts.app')

@section('headline', \App\Models\Places\Place::label())

@section('content')

<place-table index-path="{{ \App\Models\Places\Place::indexPath() }}"></place-table>

@endsection