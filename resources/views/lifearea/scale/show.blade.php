@extends('layouts.app')

@section('content')

<h1>Lifearea > <a class="text-body" href="{{ $lifearea->path }}">{{ $lifearea->title }}</a> > Level > {{ $model->value }}</h1>

<lifearea-scale-goals-table index-path="{{ \App\Models\Lifeareas\Levels\Goals\Goal::indexPath(['lifearea' => $lifearea->id, 'level' => $model->value]) }}" :attribute_groups="{{ json_encode($attribute_groups) }}"></lifearea-scale-goals-table>

@endsection