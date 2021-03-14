@extends('layouts.app')

@section('content')

<h1>Berichte</h1>

<review-table index-path="{{ \App\Models\Reviews\Review::indexPath() }}"></review-table>

@endsection