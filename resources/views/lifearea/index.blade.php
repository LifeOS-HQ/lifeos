@extends('layouts.app')

@section('content')

<h1>{{ \App\Models\Lifeareas\Lifearea::label() }}</h1>

<lifearea-table index-path="{{ \App\Models\Lifeareas\Lifearea::indexPath() }}"></lifearea-table>

@endsection