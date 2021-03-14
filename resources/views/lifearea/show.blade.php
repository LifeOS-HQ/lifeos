@extends('layouts.app')

@section('content')

<div class="d-flex">

    <h1 class="col pl-0">Lebensbereich > {{ $model->title }}</h1>
    <div class="text-right">
        <a class="btn btn-secondary btn-sm" href="/lifearea">Übersicht</a>
    </div>

</div>

<div class="row">
    <div class="col-6">

        <div class="card">
            <form action="{{ $model->path }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-header">Allgemein</div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label col-form-label-sm" for="title">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" id="title" name="title" placeholder="Name" value="{{ $model->title }}">
                            @error('title')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary btn-sm" type="submit">Speichern</button>
                </div>
            </form>
        </div>

        @if (count($model->ratings) > 0)
            <lifearea-rating-chart class="mt-3" :model="{{ json_encode($model) }}"></lifearea-rating-chart>
        @endif

    </div>

    <div class="col-6">
        <lifearea-scale-index :model="{{ json_encode($model) }}"></lifearea-scale-index>
    </div>
</div>

@endsection