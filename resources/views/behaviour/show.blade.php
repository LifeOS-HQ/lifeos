@extends('layouts.app')

@section('content')

<div class="d-flex">

    <h1 class="col pl-0">{{ \App\Models\Behaviours\Behaviour::label() }} > {{ $model->name }}</h1>
    <div class="text-right">
        <a class="btn btn-secondary btn-sm" href="{{ $model->histories_path }}">{{ \App\Models\Behaviours\History::label(1) }}</a>
        <a class="btn btn-secondary btn-sm" href="{{ $model->index_path }}">Übersicht</a>
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
                        <label class="col-sm-4 col-form-label col-form-label-sm" for="name">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $model->name }}">
                            @error('name')
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

    </div>

</div>

@endsection
