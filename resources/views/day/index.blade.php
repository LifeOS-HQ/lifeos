@extends('layouts.app')

@section('headline', \App\Models\Days\Day::label())

@section('content')

<day-table index-path="{{ \App\Models\Days\Day::indexPath() }}"></day-table>

@endsection
