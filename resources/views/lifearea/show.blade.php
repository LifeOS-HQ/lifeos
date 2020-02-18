@extends('layouts.app')

@section('content')

<div class="d-flex">

    <h1 class="col pl-0">Lebensbereich > {{ $model->title }}</h1>
    <div class="text-right">
        <a class="btn btn-secondary" href="/lifearea">Übersicht</a>
    </div>

</div>

<div class="row">
    <div class="col-8">

        <div class="card">
            <form action="{{ $model->path }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-header">Allgemein</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Name</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Name" value="{{ $model->title }}">
                        @error('title')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" type="submit">Speichern</button>
                </div>
            </form>
        </div>

        <lifearea-rating-chart class="mt-3" :model="{{ json_encode($model) }}"></lifearea-rating-chart>

    </div>

    <div class="col-4">
        <lifearea-scale-index :model="{{ json_encode($model) }}"></lifearea-scale-index>
    </div>
</div>

@endsection