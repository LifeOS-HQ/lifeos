@extends('layouts.app')

@section('content')

    <div class="row align-items-center mb-3">
        <div class="col">
            <h1 class="col mb-0 pl-0">Post > {{ $model->title }}</h1>
        </div>
        <div id="buttons" class="col-auto d-flex align-items-center justify-content-around">
            <a class="btn btn-secondary btn-sm ml-1" href="{{ $model->edit_path }}">Bearbeiten</a>
            @if(is_null($model->published_at))
                <form action="{{ $model->publish_path }}" class="ml-1" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-primary btn-sm">Veröffentlichen</button>
                </form>
            @else
                <button class="btn btn-secondary btn-sm ml-1">Veröffentlicht ({{ $model->published_at->format('d.m.Y') }})</button>
            @endif
            <a class="btn btn-secondary btn-sm ml-1" href="{{ $model->index_path }}">Übersicht</a>
            <form action="{{ $model->path }}" class="ml-1" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">{{ $model->title }}</div>
        <div class="card-body blog-post">{!! $model->body_markdown !!}</div>
    </div>

@endsection