@extends('layouts.app')

@section('content')

@section('headline', $model->label() . ' > ' . $model->wish)

@section('buttons')
    <a href="{{ $model->edit_path }}" class="btn btn-primary btn-sm ml-1"><i class="fas fa-edit"></i></a>
    <a href="{{ $model->index_path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
    @if ($model->isDeletable())
        <form action="{{ $model->path }}" class="ml-1" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm" title="Löschen"><i class="fas fa-trash"></i></button>
        </form>
    @endif
@endsection

@endsection
