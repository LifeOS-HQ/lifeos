@extends('layouts.app')

@section('content')

    <div class="row align-items-center mb-3">
        <div class="col">
            <h1 class="col mb-0 pl-0">Post > {{ $model->title }}</h1>
        </div>
        <div id="buttons" class="col-auto d-flex align-items-center justify-content-around">
            <a class="btn btn-secondary btn-sm ml-1" href="{{ $model->path }}">Übersicht</a>
        </div>
    </div>

    <form action="{{ $model->path }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group row">
            <label class="col-sm-4 col-form-label col-form-label-sm" for="title">Titel</label>
            <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') ?? $model->title }}">
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label col-form-label-sm" for="body">Text</label>
            <div class="col-sm-8">
                <textarea class="form-control form-control-sm @error('body') is-invalid @enderror" id="body" name="body" rows="20">{{ old('body') ?? $model->body }}</textarea>
                @error('body')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
        </div>

    </form>

@endsection